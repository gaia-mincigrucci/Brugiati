<?php
session_start();
if(!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') { exit(); }

$conn = new mysqli("localhost", "root", "", "sito_volontariato");

if (isset($_GET['id'])) {
    //prende id e elimina partecipante con quell id
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM partecipanti_attivita WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
$conn->close();
header("Location: admin.php");
exit();
?>