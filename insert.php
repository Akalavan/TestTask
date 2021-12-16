<?php
    include "include/db.php";
    if (session_status() == PHP_SESSION_NONE) session_start();

    function flash_session_set($key, $val) {
        $_SESSION['flash'][$key] = $_SESSION['flash'][$key].$val;
    }


    // ([A-Z]|[0-9]){2}[A-Z]{5}([A-Z]|[0-9])[A-Z]{2} - XXAAAAAXAA (S1QWERT5TY)
    // ([0-9])([A-Z]|[0-9]){2}[A-Z]{2}([A-Z]|[0-9])[-_@]([A-Z]|[0-9])[a-z]{2} - NXXAAXZXaa (5SNRT5-2qw)
    // ([0-9])([A-Z]|[0-9]){2}[A-Z]{2}([A-Z]|[0-9])[-_@]([A-Z]|[0-9])([A-Z]|[0-9]){2} - NXXAAXZXXX (5SNRT5-2ER)

    $array = "S1QWERT5TYS1QWERT7TYS1QWERT9TYS1QWERT2TY5SNRT5-2qw5SNRT5-4qwS1QCRRT5TY5SNRT5-2ER5SNRT5-5ER5SNRS5-2ER5SNAT5-2ER";
    if (count($_POST) == 0) {
        flash_session_set('message','Поля не заполнены');
    } else if ($_POST['SN'] == '') {
        flash_session_set('message','Серийный номер оборудование не введён');
    } else {
        $sn_equipment = trim($_POST['SN']);
        $id_equipment = $_POST['equipment'];

        $stmt = $db->prepare("SELECT mask_SN FROM type_equipment WHERE id = :id_equipment");
        $stmt->execute(['id_equipment' => $id_equipment]);
        $mask = $stmt->fetchColumn();

        $pattern = "/";
        for ($i = 0; $i < strlen($mask); $i++) {
            switch ($mask[$i]) {
                case 'X':
                    $pattern = $pattern . '([A-Z\d])';
                    break;
                case 'A':
                    $pattern = $pattern . '[A-Z]';
                    break;
                case 'N':
                    $pattern = $pattern . '\d';
                    break;
                case 'Z':
                    $pattern = $pattern . '[-_@]';
                    break;
                case 'a':
                    $pattern = $pattern . '[a-z]';
                    break;
            }
        }
        $pattern = $pattern . '/';

        preg_match_all($pattern, $sn_equipment, $out, PREG_SET_ORDER);

        if (count($out) > 0) {
            for ($i = 0; $i < count($out); $i++) {

                $stmt = $db->prepare("SELECT COUNT(1) FROM equipment WHERE SN = :value");
                $stmt->execute(['value' => $out[$i][0]]);
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    flash_session_set('message', 'Данный серийный номер имеется в базе данных \'' . $out[$i][0] . '\'<br>');
                } else {

                    $stmt = $db->prepare("INSERT INTO equipment (type_id, SN) VALUES (:id_equipment, :SN)");
                    $params = [
                        'id_equipment' => $id_equipment,
                        'SN' => $out[$i][0]
                    ];
                    $stmt->execute($params);
                    flash_session_set('message','Оборудование успешно занесено\''.$out[$i][0].'\'<br >');
                }
            }
        } else flash_session_set('message', 'Введён некорректный серийный номер');
    }
    header('Location: k_telecom.php');

