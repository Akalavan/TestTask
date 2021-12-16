<?php
    $host = 'localhost';
    $dbname = 'k_telecom';
    $user = 'root';
    $pass = '';

    try {
        $db = new PDO('mysql:host=localhost;port=3307;dbname=k_telecom', 'root', '');
        $db -> exec("SET NAMES UTF8");
        $db -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch (PDOException $e) {
        print $e -> getMessage();
        die();
    }

