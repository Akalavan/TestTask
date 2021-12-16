<?php
    include "include/db.php";
    if (session_status() == PHP_SESSION_NONE) session_start();

	$stmt = $db -> query('SELECT * FROM type_equipment');

    # Устанавливаем режим выборки. Возвращает массив с названиями столбцов в виде ключей
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $equipment = $stmt -> fetchAll();

	function flash_session_get($key) {
	    if (isset($_SESSION['flash'][$key])) {
	        $data = $_SESSION['flash'][$key];
	        unset($_SESSION['flash'][$key]);
	        return $data;
        } else {
	        return '';
        }
    }

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<title>K TELECOM</title>
</head>
<body>
	<form action="insert.php" method="post" style="width: 300px">
	    <p><b>Добавить новое оборудование:</b></p>
        <?php
            $data_flash = flash_session_get('message');
            if ($data_flash) echo '<b>'.$data_flash.'</b>';
        ?>
	    <p><textarea id="SN" name="SN" style="width: 100%"></textarea></p>
	    <p>
	    	<div class="input-group">
	    		<select class="custom-select" id="inputButton" name="equipment">
	    		    <?php
	    		        foreach ($equipment as $key => $item) {
                            echo "<option value=$item[id]>$item[type_name]</option>";
                        }
	    		    ?>
	    		</select>
	    		<div class="input-group-append">
	    			<button class="btn btn-outline-secondary" type="submit" >Добавить</button>
	    		</div>
	    	</div>
		</p>
	</form>
</body>
</html>