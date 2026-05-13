<?php
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //interfacciamento db        
    $host = "localhost";
    $dbname = "sito_volontariato";
    $user = "root";
    $pass = "";

    $nome =$_POST['fname'];
    $email =$_POST['email'];
    $cognome =$_POST['lname'];
    $eta =$_POST['age'];
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connessione Riuscita";
    } catch (PDOException $e) {
        die("Errore di connessione: " . $e->getMessage());
    }
    try{
    $sql = "INSERT INTO Utenti (Nome, Cognome, Eta, Email) VALUES (:Nome, :Cognome, :Eta, :Email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":Nome" => $nome, ":Cognome" => $cognome, ":Eta" => $eta, ":Email" => $email]);
    }catch(Exception $e){
        die("Errore di inserimento:" . $e->getMessage());
    }

    $mail = new PHPMailer(true);
    
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;        
    $mail->Username = 'giuliobrugiati5@gmail.com';
    $mail->Password = 'jljq urkn wvgu ypmd';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port  = 587;

    $mail->setFrom('giuliobrugiati5@gmail.com', 'Sistema Registrazione');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Conferma Registrazione';
    $mail->Body = "Ciao " . $nome . ", grazie per esserti registrato";

    $mail->send();
}
