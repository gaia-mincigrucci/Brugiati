<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sito_volontariato";

// Controlla se è stato passato un ID nell'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Assicura che sia un numero intero per sicurezza

    $conn = new mysqli($servername, $username, $password, $database);

    // Se la connessione va a buon fine, elimina l'utente
    if (!$conn->connect_error) {
        $stmt = $conn->prepare("DELETE FROM utenti WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}

// Dopo l'eliminazione, torna automaticamente alla pagina admin
header("Location: admin.php");
exit();
?>