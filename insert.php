<?php
    require_once('include/db.php');
    require_once('flashMessage.php');

    function getPattern($mask) {
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
        $pattern = $pattern.'/';
        return $pattern;
    }

    function executeQuery($query, $params, $db) {
        $stmt = $db->prepare($query);
        $stmt -> execute($params);
        return $stmt;
    }

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

        $pattern = getPattern($mask);

        preg_match_all($pattern, $sn_equipment, $out, PREG_SET_ORDER);

        if (count($out) > 0) {
            for ($i = 0; $i < count($out); $i++) {

                $stmt = executeQuery("SELECT COUNT(1) FROM equipment WHERE SN = :value", ['value' => $out[$i][0]], $db);
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    flash_session_set('message', 'Данный серийный номер имеется в базе данных \'' . $out[$i][0] . '\'<br>');
                } else {
                    $params = [
                        'id_equipment' => $id_equipment,
                        'SN' => $out[$i][0]
                    ];
                    executeQuery("INSERT INTO equipment (type_id, SN) VALUES (:id_equipment, :SN)", $params, $db);
                    flash_session_set('message','Оборудование успешно занесено\''.$out[$i][0].'\'<br >');
                }
            }
        } else flash_session_set('message', 'Введён некорректный серийный номер');
    }
    header('Location: k_telecom.php');

