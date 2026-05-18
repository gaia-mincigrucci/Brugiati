<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<ul id="menu">
    <!--menu e in base alla variabile di sessione ruolo mostra un menu diverso-->
    <li><a href="index.php">HOME</a></li>
    <li><a href="cosa.php">CHE COS'È IL VOLONTARIATO AMBIENTALE</a></li>
    <li><a href="comporta.php">CHE COSA COMPORTA IL VOLONTARIATO AMBIENTALE</a></li>
    <?php if(!isset($_SESSION['ruolo'])): ?>
        <li><a href="login.php">UNISCITI (ACCEDI)</a></li>
    <?php else: ?>
        <?php if($_SESSION['ruolo'] === 'admin'): ?>
            <li><a href="admin.php">GESTIONE</a></li>
        <?php else: ?>
            <li><a href="prenotazione.php">PRENOTAZIONE</a></li>
        <?php endif; ?>
        <li><a href="logout.php">LOGOUT</a></li>
    <?php endif; ?>
</ul>