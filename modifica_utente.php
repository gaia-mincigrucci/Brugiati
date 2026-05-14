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
    <style>
    body {
      background: linear-gradient(135deg, #a8e6a1, #7fdc86, #63c76b);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Poppins", sans-serif;
    }
    .form-container {
      width: 100%;
      max-width: 500px;
      padding: 30px;
      background-color: #f9fff9;
      border-radius: 20px;
      border: 2px solid #6cc96c;
      box-shadow: 0 6px 15px rgba(0, 100, 0, 0.2);
    }
    h2 {
      color: #2f7a2f;
      font-weight: 600;
    }
    .form-label {
      color: #2b6b2b;
      font-weight: 500;
    }
    .btn-success { background-color: #34a853; border: none; }
    .btn-success:hover { background-color: #2c8b46; transform: scale(1.03); }
    .btn-secondary { background-color: #6c757d; border: none; }
  </style>
</head>
<body>
<div class="form-container">
    <h2>Modifica Utente</h2>
    //form per modificare utenti
    <form method="POST" action="modifica_utente.php">
        <input type="hidden" name="id" value="<?php echo $utente['id']; ?>">
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($utente['email']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Password:</label>
            <input type="text" class="form-control" name="password" value="<?php echo htmlspecialchars($utente['password']); ?>" >
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