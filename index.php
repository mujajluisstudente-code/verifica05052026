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
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewpoort" content="width=device-width, initial-scale=1.0">
    <title></title>
    
</head>
<body>
    <div class="container">
        <header>
            <h1></h1>
            <p>Endpoint: <span class="endpoint-badge">/api/users</span></p>
        </header>
 <div class="panel">
            <h2> </h2>
            <div class="form-grid">
                <div class="form-group">
                    <label></label>
                    <select id="method">
                        <option value="GET"></option>

                    </select>
                </div>
