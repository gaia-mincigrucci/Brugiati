<?php
session_start();
if(!isset($_SESSION['ruolo'])) {
    header("Location: registrazione.php");
    exit();
} elseif($_SESSION['ruolo'] === 'admin') {
    header("Location: admin.php");
    exit();
}

    $nome_attivita = isset($_GET['nome_attivita']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "sito_volontariato");
    $stmt = $conn->prepare("INSERT INTO partecipanti_attivita (nome, cognome, eta, nome_attivita, data_iscrizione) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssis", $_POST['nome'], $_POST['cognome'], $_POST['eta'], $_POST['nome_attivita']);
    $stmt->execute();
    header("Location: prenotazione.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Partecipa all'attività</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="stile.css">
</head>
<body>
<div class="form-container">
  <h2 class="text-center mb-2">Partecipazione</h2>
  <div class="info-att text-center">Stai partecipando a:<br> <?php echo htmlspecialchars($nome_attivita); ?></div>
  
  <form method="POST" action="partecipa.php">
    <input type="hidden" name="nome_attivita" value="<?php echo htmlspecialchars($nome_attivita); ?>">

    <div class="mb-3">
      <label for="n" class="form-label">Nome:</label>
      <input type="text" class="form-control" id="n" name="nome" required>
    </div>

    <div class="mb-3">
      <label for="c" class="form-label">Cognome:</label>
      <input type="text" class="form-control" id="c" name="cognome" required>
    </div>

    <div class="mb-3">
      <label for="e" class="form-label">Età:</label>
      <input type="number" class="form-control" id="e" name="eta" min="1" max="99" required>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success px-5 py-2">Conferma</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>