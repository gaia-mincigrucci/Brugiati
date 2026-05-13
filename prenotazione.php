<?php
session_start();
if(!isset($_SESSION['ruolo'])) {
    header("Location: registrazione.php");
    exit();
} elseif($_SESSION['ruolo'] === 'admin') {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="stile.css">
    <title>Prenotazione Attività</title>
</head>
<body>
    <?php include 'menu.php'; ?>
    <h2>Prenota la tua partecipazione</h2>
    <div class="gallery">
        <div class="activity">
            <img src="Gemini_Generated_Image_xfrihpxfrihpxfri.png" alt="Attività trasimeno">
            <div class="description">
                <h3>Pulizia Spiaggia Magione</h3>
                <p>Unisciti a noi per la pulizia delle rive del lago Trasimeno.</p>
                <a href="partecipa.php?nome_attivita=Pulizia_Spiaggia"><button>PARTECIPA</button></a>
            </div>
        </div>
        <div class="activity">
            <img src="Gemini_Generated_Image_3eza13eza13eza13.png" alt="Attività Tevere">
            <div class="description">
                <h3>Pulizia del Tevere</h3>
                <p>Collabora alla rimozione dei rifiuti lungo gli argini del fiume Tevere.</p>
                <a href="partecipa.php?nome_attivita=Pulizia_Tevere"><button>PARTECIPA</button></a>
            </div>
        </div>
    </div>
    </div>
</body>
</html>