<?php
// profile.php

if (!isset($_SESSION['username'])) {
    header("Location: main.php?page=404");
    exit;
}

$profileFile = 'user_profile.json';
$errors = [];
$data = [
    'name' => '',
    'surname' => '',
    'birthdate' => '',
    'about' => '',
    'photo' => ''
];

if (file_exists($profileFile)) {
    $json = file_get_contents($profileFile);
    $data = json_decode($json, true) ?? $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $about = trim($_POST['about'] ?? '');
    $photo = $data['photo'];

    if ($name === '' || strlen($name) < 2) {
        $errors[] = 'Ім\'я повинно бути не менше 2 символів.';
    }
    if ($surname === '' || strlen($surname) < 2) {
        $errors[] = 'Прізвище повинно бути не менше 2 символів.';
    }
    if ($birthdate === '' || (time() - strtotime($birthdate)) < 16 * 365 * 24 * 60 * 60) {
        $errors[] = 'Вам має бути не менше 16 років.';
    }
    if (strlen($about) < 50) {
        $errors[] = 'Про себе повинно бути не менше 50 символів.';
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['photo']['type'], $allowed)) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $fileName = 'uploads/' . uniqid('photo_', true) . "." . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $fileName);
            $photo = $fileName;
        } else {
            $errors[] = 'Недопустимий формат фото.';
        }
    }

    if (empty($errors)) {
        $data = [
            'name' => $name,
            'surname' => $surname,
            'birthdate' => $birthdate,
            'about' => $about,
            'photo' => $photo
        ];
        file_put_contents($profileFile, json_encode($data));
        header('Location: main.php?page=profile');
        exit;
    }
}
?>

<h1>Профіль користувача</h1>
<?php if ($errors): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Ім'я: <input name="name" value="<?= htmlspecialchars($data['name']) ?>"></label><br>
    <label>Прізвище: <input name="surname" value="<?= htmlspecialchars($data['surname']) ?>"></label><br>
    <label>Дата народження: <input type="date" name="birthdate" value="<?= htmlspecialchars($data['birthdate']) ?>"></label><br>
    <label>Про себе:<br><textarea name="about" rows="5" cols="40"><?= htmlspecialchars($data['about']) ?></textarea></label><br>
    <label>Фото: <input type="file" name="photo"></label><br>
    <?php if ($data['photo']): ?><img src="<?= $data['photo'] ?>" width="150"><br><?php endif; ?>
    <button type="submit">Зберегти</button>
</form>

<?php include 'footer.php'; ?>
