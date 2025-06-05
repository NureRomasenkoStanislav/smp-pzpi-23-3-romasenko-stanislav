<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $creds = require 'credential.php';
    $inputUser = $_POST['username'] ?? '';
    $inputPass = $_POST['password'] ?? '';
    if ($inputUser === $creds['username'] && $inputPass === $creds['password']) {
        $_SESSION['username'] = $inputUser;
        $_SESSION['login_time'] = date('Y-m-d H:i:s');
        header('Location: main.php?page=products');
        exit;
    } else {
        $error = "Неправильний логін або пароль.";
    }
}
?>

<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" action="main.php?page=login">
    <label>Ім’я користувача: <input type="text" name="username" required></label><br>
    <label>Пароль: <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>
