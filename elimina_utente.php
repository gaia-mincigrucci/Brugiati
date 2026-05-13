<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sito_volontariato";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $conn = new mysqli($servername, $username, $password, $database);

    if (!$conn->connect_error) {
        $stmt = $conn->prepare("DELETE FROM utenti WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
header("Location: admin.php");
exit();
?>