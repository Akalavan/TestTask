<?php
    require_once('include/db.php');
    require_once('flashMessage.php');

	$stmt = $db -> query('SELECT * FROM type_equipment');
    # Устанавливаем режим выборки. Возвращает массив с названиями столбцов в виде ключей
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $equipment = $stmt -> fetchAll();
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