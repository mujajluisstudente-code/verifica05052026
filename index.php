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
                }
                ?>
            </select>
            <button type="submit" name="aggiungi_iscrizione">Aggiungi Iscrizione</button>
        </form>
    </div>
     <php elseif($sezione == 'corsi_popolari'): ?>

         <div class="sezione">
        <h2>Corsi con almeno 5 iscritti e relativo istruttore</h2>
        <table>
            <tr>
                <th>Istruttore</th>
                <th>Corso</th>
                <th>Numero Iscritti</th>
            </tr>
            <?php
            $sql = "SELECT i.nome, i.cognome, c.nome_corso, COUNT(ic.id_iscrizione) as totale_iscritti
                    FROM Istruttori i
                    JOIN Corsi c ON i.id_istruttore = c.id_istruttore
                    JOIN Iscrizioni_Corsi ic ON c.id_corso = ic.id_corso
                    GROUP BY c.id_corso
                    HAVING totale_iscritti >= 5
                    ORDER BY totale_iscritti DESC";
            $stmt = $pdo->query($sql);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['nome']} {$row['cognome']}</td>
                        <td>{$row['nome_corso']}</td>
                        <td>{$row['totale_iscritti']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>
    
    <?php elseif($sezione == 'cambia_corso'): ?>
             <div class="sezione">
        <h2>Cambia corso ad un iscritto</h2>
        <?php
        $corso_selezionato = isset($_GET['corso_id']) ? $_GET['corso_id'] : null;
        
        $sql_corsi = "SELECT id_corso, nome_corso FROM Corsi ORDER BY nome_corso";
        $corsi = $pdo->query($sql_corsi);
        ?>
        
        <form method="GET">
            <label>Scegli corso per visualizzare gli iscritti:</label>
            <select name="corso_id" onchange="this.form.submit()">
                <option value="">Seleziona corso</option>
                <?php while($corso = $corsi->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $corso['id_corso']; ?>" <?php echo ($corso_selezionato == $corso['id_corso']) ? 'selected' : ''; ?>>
                        <?php echo $corso['nome_corso']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
        
        <?php if($corso_selezionato): 
            $sql_iscritti = "SELECT ic.id_iscrizione, m.id_membro, m.nome, m.cognome, c.nome_corso as corso_attuale
                            FROM Iscrizioni_Corsi ic
                            JOIN Membri m ON ic.id_membro = m.id_membro
                            JOIN Corsi c ON ic.id_corso = c.id_corso
                            WHERE ic.id_corso = ?
                            ORDER BY m.cognome, m.nome";
            $stmt = $pdo->prepare($sql_iscritti);
            $stmt->execute([$corso_selezionato]);
            $iscritti = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $sql_tutti_corsi = "SELECT id_corso, nome_corso FROM Corsi WHERE id_corso != ? ORDER BY nome_corso";
            $stmt2 = $pdo->prepare($sql_tutti_corsi);
            $stmt2->execute([$corso_selezionato]);
            $tutti_corsi = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            ?>
       
            
