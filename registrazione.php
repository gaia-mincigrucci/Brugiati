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
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    if ($email === "giuliobrugiati5@gmail.com") {
        $ruolo = "admin";
    } else {
        $ruolo = "utente";
    }

    $host = "localhost";
    $dbname = "sito_volontariato";
    $user = "root";
    $pass = "";
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmtCheck = $pdo->prepare("SELECT ruolo FROM utenti WHERE Email = :Email");
        $stmtCheck->execute([':Email' => $email]);
        $userEsistente = $stmtCheck->fetch();
        if ($userEsistente) {
            $_SESSION['email'] = $email;
            $_SESSION['ruolo'] = $userEsistente['ruolo'];
            header("Location: " . ($_SESSION['ruolo'] === 'admin' ? "admin.php" : "prenotazione.php"));
            exit();
        }

        $sql = "INSERT INTO utenti (Email, password, ruolo) VALUES (:Email, :password, :ruolo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":Email" => $email,
            ":password" => $password_hash,
            ":ruolo" => $ruolo
        ]);

        $_SESSION['email'] = $email;
        $_SESSION['ruolo'] = $ruolo;

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'giuliobrugiati5@gmail.com';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Password = 'cntq wzgs amte ekfu';
        $mail->Port = 587;
        
        $mail->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
          )
        );

        $mail->setFrom('giuliobrugiati5@gmail.com', 'Volontariato');
        $mail->addAddress($email);
        $mail->Subject = 'Benvenuto nel nostro team!';
        $mail->Body = "Grazie per esserti unito a noi. La tua registrazione con l'email $email è avvenuta con successo.";
        $mail->send();

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
  <title>Registrazione</title>
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
        password: password,
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
    
    return true;
  }
</script>
</body>
</html>