<?php
session_unset();
session_destroy();
header('Location: main.php?page=login');
exit;
