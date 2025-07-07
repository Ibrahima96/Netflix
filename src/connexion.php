<?php
$host = "localhost";
$db = "netflix";
$user = "root";
$pass = "";

try {
    $bdd = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error de connexion :' . $e->getMessage());
}
