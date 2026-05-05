<?php
session_start();

if(isset($_POST['login'])) {
    $cognome = $_POST['cognome'];
    $password = $_POST['password'];
    
    if($password == "verifica") {
        $_SESSION['logged_in'] = true;
        $_SESSION['cognome'] = $cognome;
        header('Location: index.php');
        exit();
    } else {
        $errore = "Password sbagliata";
    }
}
