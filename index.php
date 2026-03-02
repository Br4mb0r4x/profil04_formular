<?php
$file = 'profile.json';
$message = '';
$messageType = ''; 


if (!file_exists($file)) {
    if (file_put_contents($file, json_encode([])) === false) {
        $message = "Nepodařilo se vytvořit datový soubor.";
        $messageType = "error";
    }
}


$data = @file_get_contents($file);

if ($data === false) {
    $interests = [];
    $message = "Soubor se nepodařilo načíst.";
    $messageType = "error";
} else {
    $interests = json_decode($data, true);
}


if (!is_array($interests)) {
    $interests = [];
}


if (isset($_POST['new_interest'])) {

    $newInterest = trim($_POST['new_interest']);


    if ($newInterest === '') {
        $message = "Zájem nesmí být prázdný.";
        $messageType = "warning";
    } else {


        $lowerInterests = array_map('strtolower', $interests);

        if (in_array(strtolower($newInterest), $lowerInterests)) {
            $message = "Tento zájem už je v seznamu.";
            $messageType = "warning";
        } else {


            $interests[] = $newInterest;


            $saved = @file_put_contents(
                $file,
                json_encode($interests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            if ($saved === false) {
                $message = "Chyba při ukládání dat!";
                $messageType = "error";
            } else {
                $message = "Zájem byl úspěšně přidán.";
                $messageType = "success";
            }
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

echo '
<form method="POST">
    <input type="text" name="new_interest" required>
    <button type="submit">Přidat zájem</button>
</form>
';


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