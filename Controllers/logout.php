<?php
require 'AuthController.php';
require '../config/Database.php';
require '../Models/users.php';
session_start();
(new AuthController(Database::getInstance()->getConnection()))->logout();
?>