
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Весна - Продуктовий магазин</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>Весна - Продуктовий магазин</h1>
    <nav>
        <a href="main.php?page=products">Товари</a>
        <a href="main.php?page=cart">Кошик</a>
        <a href="main.php?page=profile">Профіль</a>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="main.php?page=login">Login</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">
