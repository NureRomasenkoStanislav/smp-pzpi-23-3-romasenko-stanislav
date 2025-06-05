<?php
$cart = $_SESSION['cart'] ?? [];

if (isset($_GET['remove'])) {
    unset($cart[(int)$_GET['remove']]);
    $_SESSION['cart'] = $cart;
    header('Location: main.php?page=cart');
    exit;
}
?>

<h2>Ваш кошик</h2>
<?php if (empty($cart)): ?>
    <p>Кошик порожній. <a href="main.php?page=products">Назад до покупок</a></p>
<?php else: ?>
    <table border="1">
        <tr><th>ID</th><th>Назва</th><th>Ціна</th><th>Кількість</th><th>Сума</th><th>Дія</th></tr>
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
            <td><a href="main.php?page=cart&remove=<?= $item['id'] ?>">Видалити</a></td>
        </tr>
        <?php endforeach; ?>
        <tr><td colspan="4"><strong>Загалом</strong></td><td colspan="2"><strong>$<?= $total ?></strong></td></tr>
    </table>
<?php endif; ?>
