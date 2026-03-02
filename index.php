<?php
$file = 'profile.json';

// vytvoření souboru pokud neexistuje
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

// načtení existujících zájmů
$interests = json_decode(file_get_contents($file), true);

// pojistka – vždy musí být pole
if (!is_array($interests)) {
    $interests = [];
}

// ==========================
// ZPRACOVÁNÍ FORMULÁŘE
// ==========================
$message = '';

if (isset($_POST['new_interest'])) {

    // očištění vstupu
    $newInterest = trim($_POST['new_interest']);

    // kontrola prázdného vstupu
    if ($newInterest === '') {
        $message = "Zájem nesmí být prázdný.";
    } else {

        // kontrola duplicit (bez ohledu na velikost písmen)
        $lowerInterests = array_map('strtolower', $interests);

        if (in_array(strtolower($newInterest), $lowerInterests)) {
            $message = "Tento zájem už existuje.";
        } else {

            // přidání nového zájmu
            $interests[] = $newInterest;

            // uložení do JSON
            file_put_contents(
                $file,
                json_encode($interests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            // znovu načíst data (jistota)
            $interests = json_decode(file_get_contents($file), true);

            $message = "Zájem byl přidán.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Požadavky</title>
    <?php echo '<link rel="stylesheet" href="style.css">'; ?>
</head>
<body>

<h2>Přidej zájem</h2>

<?php
// ==========================
// FORMULÁŘ VYGENEROVANÝ PHP
// ==========================
echo '
<form method="POST">
    <input type="text" name="new_interest" required>
    <button type="submit">Přidat zájem</button>
</form>
';

// výpis zprávy
if ($message !== '') {
    echo "<p><strong>$message</strong></p>";
}
?>

<h3>Seznam zájmů:</h3>
<ul>
<?php
foreach ($interests as $interest) {
    echo "<li>" . htmlspecialchars($interest) . "</li>";
}
?>
</ul>

</body>
</html>