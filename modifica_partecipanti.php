<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sito_volontariato";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// 1. SE IL FORM È STATO INVIATO (Salvataggio delle modifiche)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nome = $_POST['fname'];
    $cognome = $_POST['lname'];
    $eta = $_POST['age'];
    $email = $_POST['email'];

    // Aggiorna i dati nel database
    $stmt = $conn->prepare("UPDATE utenti SET nome=?, Cognome=?, Eta=?, Email=? WHERE id=?");
    $stmt->bind_param("ssisi", $nome, $cognome, $eta, $email, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    // Torna alla pagina admin
    header("Location: admin.php");
    exit();
}

// 2. SE STIAMO CARICANDO LA PAGINA (Lettura dei dati attuali da mostrare nel form)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM utenti WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $utente = $result->fetch_assoc();
    $stmt->close();
    
    if (!$utente) {
        die("Utente non trovato!");
    }
} else {
    // Se non c'è nessun ID, rimanda alla home o admin
    header("Location: admin.php");
    exit();
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
  <h2 class="text-center mb-4">Modifica Utente</h2>
  
  <form method="POST" action="modifica_utente.php">
    <input type="hidden" name="id" value="<?php echo $utente['id']; ?>">
    
    <div class="mb-3">
      <label for="fname" class="form-label">Nome:</label>
      <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($utente['nome']); ?>" required>
    </div>
    <div class="mb-3">
      <label for="lname" class="form-label">Cognome:</label>
      <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($utente['cognome']); ?>" required>
    </div>
    <div class="mb-3">
      <label for="age" class="form-label">Età:</label>
      <input type="number" class="form-control" id="age" name="age" min="0" max="100" value="<?php echo htmlspecialchars($utente['eta']); ?>" required>
    </div>
    <div class="mb-3">
      <label for="attivita" class="form-label">Nome Attività:</label>
      <input type="text" class="form-control" id="attivita" name="attivita" value="<?php echo htmlspecialchars($utente['nome_attivita']); ?>" required>
    </div>
    <div class="mb-3">
      <label for="data_iscrizione" class="form-label">Data Iscrizione:</label>
      <input type="text" class="form-control" id="data_iscrizione" name="data_iscrizione" value="<?php echo htmlspecialchars($utente['data_iscrizione']); ?>" required>
    </div>
    <div class="text-center mt-4">
      <button type="submit" class="btn btn-success px-4 py-2 me-2"> Salva Modifiche</button>
      <a href="admin.php" class="btn btn-secondary px-4 py-2">Annulla</a>
    </div>
  </form>
</div>

</body>
</html>