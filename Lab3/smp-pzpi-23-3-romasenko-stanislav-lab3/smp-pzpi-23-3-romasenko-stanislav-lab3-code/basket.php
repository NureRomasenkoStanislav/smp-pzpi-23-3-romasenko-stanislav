<?php
session_start();
$cart = $_SESSION['cart'] ?? [];

if (isset($_GET['remove'])) {
    $removeId = (int)$_GET['remove'];
    unset($cart[$removeId]);
    $_SESSION['cart'] = $cart;
    header('Location: basket.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Кошик</title>
</head>
<body>
    <h1>Ваш кошик</h1>

    <?php if (empty($cart)): ?>
        <p>Ваш кошик порожній. <a href="index.php">Перейти до покупок</a></p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>ID</th><th>Назва</th><th>Ціна</th><th>Кількість</th><th>Сума</th><th>Дія</th>
            </tr>
            <?php
            $total = 0;
            foreach ($cart as $item):
                $sum = $item['price'] * $item['count'];
                $total += $sum;
            ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>$<?= $item['price'] ?></td>
                    <td><?= $item['count'] ?></td>
                    <td>$<?= $sum ?></td>
                    <td><a href="basket.php?remove=<?= $item['id'] ?>">Видалити</a></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4"><strong>Загалом</strong></td>
                <td colspan="2"><strong>$<?= $total ?></strong></td>
            </tr>
        </table>
        <br>
        <a href="index.php">Назад до покупок</a>
    <?php endif; ?>
</body>
</html>
