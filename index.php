<?php
$file = 'profile.json';
$message = '';
$messageType = '';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$data = file_get_contents($file);
$interests = json_decode($data, true);

if (!is_array($interests)) {
    $interests = [];
}


# --------------------
# SMAZÁNÍ ZÁJMU
# --------------------
if (isset($_POST['delete'])) {
    $index = $_POST['delete'];

    if (isset($interests[$index])) {
        unset($interests[$index]);
        $interests = array_values($interests);

        file_put_contents($file, json_encode($interests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $message = "Zájem byl smazán.";
    }
}


# --------------------
# EDITACE ZÁJMU
# --------------------
if (isset($_POST['edit_index'])) {

    $index = $_POST['edit_index'];
    $newValue = trim($_POST['edit_value']);

    if ($newValue === '') {
        $message = "Nová hodnota nesmí být prázdná.";
    } else {

        $lower = array_map('strtolower', $interests);

        if (in_array(strtolower($newValue), $lower)) {
            $message = "Takový zájem už existuje.";
        } else {

            $interests[$index] = $newValue;

            file_put_contents($file, json_encode($interests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $message = "Zájem byl upraven.";
        }
    }
}


# --------------------
# PŘIDÁNÍ ZÁJMU
# --------------------
if (isset($_POST['new_interest'])) {

    $newInterest = trim($_POST['new_interest']);

    if ($newInterest === '') {
        $message = "Zájem nesmí být prázdný.";
    } else {

        $lowerInterests = array_map('strtolower', $interests);

        if (in_array(strtolower($newInterest), $lowerInterests)) {
            $message = "Tento zájem už je v seznamu.";
        } else {

            $interests[] = $newInterest;

            file_put_contents($file, json_encode($interests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $message = "Zájem byl přidán.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<title>Zájmy</title>
</head>
<body>

<h2>Přidat zájem</h2>

<form method="POST">
    <input type="text" name="new_interest" required>
    <button type="submit">Přidat</button>
</form>

<p><?php echo $message; ?></p>

<h3>Seznam zájmů</h3>

<ul>

<?php foreach ($interests as $index => $interest): ?>

<li>

<form method="POST" style="display:inline;">

<input type="text" name="edit_value" value="<?php echo htmlspecialchars($interest); ?>">

<input type="hidden" name="edit_index" value="<?php echo $index; ?>">

<button type="submit">Upravit</button>

</form>


<form method="POST" style="display:inline;">

<button type="submit" name="delete" value="<?php echo $index; ?>">
Smazat
</button>

</form>

</li>

<?php endforeach; ?>

</ul>

</body>
</html>