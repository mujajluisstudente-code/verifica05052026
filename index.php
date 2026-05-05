<?php
require_once 'config.php';
checkLogin();

$messaggio = '';

if(isset($_POST['aggiungi_iscrizione'])) {
    $id_corso = $_POST['id_corso'];
    $id_membro = $_POST['id_membro'];
    $data_iscrizione = date('Y-m-d');
    
    $sql = "INSERT INTO Iscrizioni_Corsi (id_corso, id_membro, data_iscrizione) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([$id_corso, $id_membro, $data_iscrizione])) {
        $messaggio = "Iscrizione aggiunta";
    } else {
        $messaggio = "Errore";
    }
}

if(isset($_POST['cambia_corso'])) {
    $id_iscrizione = $_POST['id_iscrizione'];
    $nuovo_corso = $_POST['nuovo_corso'];
    
    $sql = "UPDATE Iscrizioni_Corsi SET id_corso = ? WHERE id_iscrizione = ?";
    $stmt = $pdo->prepare($sql);
    if($stmt->execute([$nuovo_corso, $id_iscrizione])) {
        $messaggio = "Corso cambiato";
    } else {
        $messaggio = "Errore";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestione Palestra</title>

</head>
<body>
    <div class="menu">
        <strong><?php echo $_SESSION['cognome']; ?></strong>
        <a href="?sezione=nuova_iscrizione">Nuova Iscrizione</a>
        <a href="?sezione=corsi_popolari">Corsi con piu di 5 iscritti</a>
        <a href="?sezione=cambia_corso">Cambia Corso Iscritti</a>
        <a href="?sezione=report">Report Completo</a>
    </div>
    
    <?php if($messaggio): ?>
        <div class="messaggio"><?php echo $messaggio; ?></div>
    <?php endif; ?>
    
    <?php
    $sezione = isset($_GET['sezione']) ? $_GET['sezione'] : 'nuova_iscrizione';
    
    if($sezione == 'nuova_iscrizione'):
    ?>
     <div class="sezione">
        <h2>Nuova iscrizione ad un corso</h2>
        <form method="POST">
            <label>Scegli iscritto:</label>
            <select name="id_membro" required>
                <option value="">Seleziona iscritto</option>
                <?php
                $sql = "SELECT id_membro, nome, cognome FROM Membri ORDER BY cognome, nome";
                $stmt = $pdo->query($sql);
                }
                ?>
            </select>
            
        <label>Scegli Corso:</label>
            <select name="id_corso" required>
                <option value="">Seleziona corso</option>
                <?php
                $sql = "SELECT c.id_corso, c.nome_corso, i.nome, i.cognome 
                        FROM Corsi c 
                        JOIN Istruttori i ON c.id_istruttore = i.id_istruttore 
                        ORDER BY c.nome_corso";
                $stmt = $pdo->query($sql);
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['id_corso']}'>{$row['nome_corso']} (Istruttore: {$row['nome']} {$row['cognome']})</option>";
                }
                ?>
            </select>
