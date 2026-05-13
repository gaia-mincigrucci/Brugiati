<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // 1. HASHING DELLA PASSWORD
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    
    // Controllo Admin
    $ruolo = ($email === "giuliobrugiati5@gmail.com") ? 'admin' : 'utente';

    $host = "localhost";
    $dbname = "sito_volontariato";
    $user = "root";
    $pass = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Inserimento con password hashata
        $sql = "INSERT INTO utenti (Email, password, ruolo) VALUES (:Email, :password, :ruolo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":Email" => $email,
            ":password" => $password_hashed,
            ":ruolo" => $ruolo
        ]);

        $_SESSION['email'] = $email;
        $_SESSION['ruolo'] = $ruolo;

        // 2. INVIO EMAIL CON PHPMAILER
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'giuliobrugiati5@gmail.com';
        $mail->Password = 'tua_password_app'; // Inserisci qui la tua password per app di Google
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('giuliobrugiati5@gmail.com', 'Volontariato');
        $mail->addAddress($email);
        $mail->Subject = 'Benvenuto nel nostro team!';
        $mail->Body = "Grazie per esserti unito a noi. La tua registrazione con l'email $email è avvenuta con successo.";
        $mail->send();

        // Reindirizzamento in base al ruolo
        if ($ruolo === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: prenotazione.php");
        }
        exit();

    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrazione / Accesso</title>
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
    .btn-success {
      background-color: #34a853;
      border: none;
      transition: all 0.3s ease;
    }
    .btn-success:hover {
      background-color: #2c8b46;
      transform: scale(1.03);
    }
  </style>
</head>
<body>
<div class="form-container">
  <h2 class="text-center mb-4">Registrazione</h2>
  <form id="registration-form" method="POST" action="registrazione.php">
    <div class="mb-3">
      <label for="email" class="form-label">Email:</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password:</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="text-center">
      <button type="submit" onclick="return salvainjson()" class="btn btn-success px-4 py-2">Registrati</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function salvainjson() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    const emailPattern = /\w{1}@[a-z]{2,}\.[a-z]{2,}/;    

    let stringMessage = "";
    if (!emailPattern.test(email)) {
      stringMessage += "Inserisci un'email valida (es. esempio@gmail.com).";
    }
    
    if(stringMessage !== ""){
      alert("Gli errori sono :\n"+ stringMessage);
      return false;
    }

    // 3. LOGICA JSON COME RICHIESTO
    const dati = {
        email: email,
        password: password, // Nel JSON del browser appare in chiaro per l'utente, nel DB sarà hashatata
    };
    const jsonString = JSON.stringify(dati, null, 2);
    const blob = new Blob([jsonString], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'registrazione.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    return true; // Prosegue con l'invio del form al PHP
  }
</script>
</body>
</html>