<?php
session_start();
require_once 'header.php';

$page = $_GET['page'] ?? 'products';

$protectedPages = ['products', 'cart', 'profile'];
if (in_array($page, $protectedPages) && !isset($_SESSION['username'])) {
    $page = 'page404';
}

switch ($page) {
    case 'products':
        require 'products.php';
        break;
    case 'cart':
        require 'cart.php';
        break;
    case 'profile':
        require 'profile.php';
        break;
    case 'login':
        require 'login.php';
        break;
    case 'logout':
        require 'logout.php';
        break;
    default:
        require 'page404.php';
        break;
}

require_once 'footer.php';
