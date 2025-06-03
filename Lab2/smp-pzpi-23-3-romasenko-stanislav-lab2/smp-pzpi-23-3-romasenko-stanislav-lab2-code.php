#!/usr/bin/env php
<?php

$products = [
    1 => ["name" => "Молоко пастеризоване", "price" => 12],
    2 => ["name" => "Хліб чорний", "price" => 9],
    3 => ["name" => "Сир білий", "price" => 21],
    4 => ["name" => "Сметана 20%", "price" => 25],
    5 => ["name" => "Кефір 1%", "price" => 19],
    6 => ["name" => "Вода газована", "price" => 18],
    7 => ["name" => "Печиво \"Весна\"", "price" => 14],
];

$cart = [];
$user = ["name" => null, "age" => null];

function showMainMenu() {
    echo "################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
}

function input($msg) {
    echo $msg;
    return trim(fgets(STDIN));
}

function padName($name) {
    return mb_str_padx($name, 40, " ");
}

function showProducts($products) {
    echo "№  НАЗВА                                    ЦІНА\n";
    echo "                                            ----\n";
    foreach ($products as $num => $p) {
        echo sprintf("%-3d%s %s\n", $num, padName($p['name']), $p['price']);
    }
    echo "   -----------\n";
    echo "0  ПОВЕРНУТИСЯ\n";
}

function updateCart(&$cart, $products) {
    while (true) {
        showProducts($products);
        $input = input("Виберіть товар: ");

        if (!is_numeric($input)) {
            echo "ПОМИЛКА! Введіть номер товару.\n";
            continue;
        }

        $item = intval($input);

        if ($item === 0) return;
        if (!isset($products[$item])) {
            echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
            continue;
        }

        echo "Вибрано: {$products[$item]['name']}\n";
        $qty = input("Введіть кількість, штук: ");
        if (!is_numeric($qty) || $qty < 0 || $qty >= 100) {
            echo "ПОМИЛКА! Введіть коректну кількість товару.\n";
            continue;
        }

        $qty = intval($qty);
        if ($qty === 0) {
            unset($cart[$item]);
            echo "ВИДАЛЯЮ З КОШИКА\n";
        } else {
            $cart[$item] = $qty;
        }

        if (empty($cart)) {
            echo "КОШИК ПОРОЖНІЙ\n";
        } else {
            echo "У КОШИКУ:\nНАЗВА                                      КІЛЬКІСТЬ\n";
            foreach ($cart as $id => $count) {
                echo sprintf("%s %d\n", padName($products[$id]['name']), $count);
            }
        }
    }
}

function showBill($cart, $products) {
    if (empty($cart)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }
    echo "№  НАЗВА                                   ЦІНА   КІЛЬКІСТЬ   ВАРТІСТЬ\n";
    $i = 1;
    $total = 0;
    foreach ($cart as $id => $qty) {
        $name = padName($products[$id]['name']);
        $price = $products[$id]['price'];
        $sum = $price * $qty;
        printf("%-3d%s  %-6d   %-9d   %-8d\n", $i++, $name, $price, $qty, $sum);
        $total += $sum;
    }
    echo "РАЗОМ ДО СПЛАТИ: $total\n";
}

function setupProfile(&$user) {
    while (true) {
        $name = input("Ваше імʼя: ");
        if (!preg_match("/\pL/u", $name)) {
            echo "Імʼя користувача не може бути порожнім і повинно містити хоча б одну літеру.\n";
            continue;
        }
        $age = input("Ваш вік: ");
        if (!is_numeric($age) || $age < 7 || $age > 150) {
            echo "Користувач не може бути молодшим 7-ми або старшим 150-ти років.\n";
            continue;
        }
        $user["name"] = $name;
        $user["age"] = intval($age);
        echo "Профіль оновлено: {$user['name']}, {$user['age']} років\n";
        return;
    }
}
if (!function_exists('mb_str_padx')) {
    function mb_str_padx($input, $pad_length, $pad_string = " ", $pad_type = STR_PAD_RIGHT, $encoding = "UTF-8") {
        $diff = $pad_length - mb_strlen($input, $encoding);
        if ($diff <= 0) return $input;
        switch ($pad_type) {
            case STR_PAD_LEFT:
                return str_repeat($pad_string, $diff) . $input;
            case STR_PAD_BOTH:
                $left = floor($diff / 2);
                $right = $diff - $left;
                return str_repeat($pad_string, $left) . $input . str_repeat($pad_string, $right);
            case STR_PAD_RIGHT:
            default:
                return $input . str_repeat($pad_string, $diff);
        }
    }
}

while (true) {
    showMainMenu();
    $cmd = input("Введіть команду: ");

    switch ($cmd) {
        case "1":
            updateCart($cart, $products);
            break;
        case "2":
            showBill($cart, $products);
            break;
        case "3":
            setupProfile($user);
            break;
        case "0":
            exit("До побачення!\n");
        default:
            echo "ПОМИЛКА! Введіть правильну команду\n";
    }
}
