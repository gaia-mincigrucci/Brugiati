<?php
session_start();

if (!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin'){
    header("Location: registrazione.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "sito_volontariato";


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error){
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = intval($_POST['id']);
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $ruolo = $_POST['ruolo'];
    $stmt = $conn->prepare("UPDATE utenti SET Email=?, password=?, ruolo=? WHERE id=?");
    $stmt->bind_param("sssi", $email, $pass, $ruolo, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: admin.php");
    exit();
}

if (isset($_GET['id'])){
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM utenti WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $utente = $result->fetch_assoc();
    $stmt->close();
    
    if (!$utente){
        die("Utente non trovato!");
    }else{
    header("Location: admin.php");
    exit();
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="stili.css">
</head>
<body>
<div class="form-container">
    <h2>Modifica Utente</h2>
    <form method="POST" action="modifica_utente.php">
        <input type="hidden" name="id" value="<?php echo $utente['id']; ?>">
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($utente['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password:</label>
            <input type="text" class="form-control" name="password" value="<?php echo htmlspecialchars($utente['password']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ruolo:</label>
            <select class="form-select" name="ruolo" required>
                <option value="user" <?php if($utente['ruolo'] == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if($utente['ruolo'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success me-2">Salva Modifiche</button>
            <a href="admin.php" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>
</body>
</html>