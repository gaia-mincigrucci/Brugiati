<?php
session_start();

if(!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') {
    header("Location: registrazione.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sito_volontariato");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stile.css">
    <title>Admin</title>
</head>
<body>
    <!--tabelle per utenti e partecipanti -->
    <?php include 'menu.php'; ?>
    <div class="container-admin">
        <h1>Pannello di Controllo</h1>

        <h2>1.Utenti</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Ruolo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql1 = "SELECT id, Email, password, ruolo FROM utenti";
                $result1 = $conn->query($sql1);
                if ($result1->num_rows > 0){
                    while ($row = $result1->fetch_assoc()){
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . htmlspecialchars($row["Email"]) . "</td>";
                        echo "<td class='password-cell'>" . htmlspecialchars($row["password"]) . "</td>";
                        echo "<td>" . $row["ruolo"] . "</td>";
                        echo "<td>";
                        //bottoni per modifica e elimina
                        echo "<form action='modifica_utente.php' method='GET'>";
                        echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                        echo "<button type='submit'>MODIFICA</button>";
                        echo "</form>";
                        echo "<form action='elimina_utente.php' method='GET' onsubmit='return confirm(\"Sei sicuro di voler eliminare?\")'>";
                        echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                        echo "<button type='submit'>ELIMINA</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
            }
            else
            {
                echo "<tr><td colspan='5'>Nessun utente trovato.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <h2>2.Partecipanti</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Età</th>
                    <th>Attività</th>
                    <th>Data Iscrizione</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql2 = "SELECT id, nome, cognome, eta, nome_attivita, data_iscrizione FROM partecipanti_attivita";
                $result2 = $conn->query($sql2);

                if ($result2->num_rows > 0)
                {
                    while ($row2 = $result2->fetch_assoc())
                    {
                        echo "<tr>";
                        echo "<td>" . $row2["id"] . "</td>";
                        echo "<td>" . htmlspecialchars($row2["nome"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row2["cognome"]) . "</td>";
                        echo "<td>" . $row2["eta"] . "</td>";
                        echo "<td>" . htmlspecialchars($row2["nome_attivita"]) . "</td>";
                        echo "<td>" . $row2["data_iscrizione"] . "</td>";
                        echo "<td>";
                        //bottoni per modifica e elimina
                        echo "<form action='modifica_partecipanti.php' method='GET'>";
                        echo "<input type='hidden' name='id' value='" . $row2["id"] . "'>";
                        echo "<button type='submit'>MODIFICA</button>";
                        echo "</form>";
                        echo "<form action='elimina_partecipante.php' method='GET' onsubmit='return confirm(\"Sei sicuro di voler eliminare?\")'>";
                        echo "<input type='hidden' name='id' value='" . $row2["id"] . "'>";
                        echo "<button type='submit'>ELIMINA</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                else
                {
                    echo "<tr><td colspan='6'>Nessun partecipante trovato.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>