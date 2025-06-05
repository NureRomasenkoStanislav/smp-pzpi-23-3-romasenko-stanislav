<?php
$products = [
    ['id' => 1, 'name' => 'Fanta', 'price' => 1],
    ['id' => 2, 'name' => 'Sprite', 'price' => 1],
    ['id' => 3, 'name' => 'Nuts', 'price' => 2],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($products as $product) {
        $id = $product['id'];
        $count = (int)($_POST["count_$id"] ?? 0);
        if ($count > 0) {
            $_SESSION['cart'][$id] = [
                'id' => $id,
                'name' => $product['name'],
                'price' => $product['price'],
                'count' => $count
            ];
        }
    }
    header('Location: main.php?page=cart');
    exit;
}
?>

<h2>Сторінка товарів</h2>
<form method="POST">
    <table>
        <tr><th>Назва</th><th>Ціна</th><th>Кількість</th></tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>$<?= $product['price'] ?></td>
                <td><input type="number" name="count_<?= $product['id'] ?>" min="0" value="0"></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit">Купити</button>
</form>
