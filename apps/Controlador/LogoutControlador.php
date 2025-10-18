<?php
session_start();

$_SESSION = [];

session_destroy();

header("Location: /Proyecto/public/index.php");
exit();
