<?php
    if (session_status() == PHP_SESSION_NONE) session_start();

    function flash_session_set($key, $val) {
        $_SESSION['flash'][$key] = $_SESSION['flash'][$key].$val;
    }

    function flash_session_get($key) {
        if (isset($_SESSION['flash'][$key])) {
            $data = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $data;
        } else {
            return '';
        }
    }
