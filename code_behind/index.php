<?php  
	
	//$usuarios = $obj->get_results("SELECT * FROM ejemplo_tabla");
	//$usuario = $obj->get_row("SELECT * FROM ejemplo_tabla WHERE id_ejemplo_tabla = 1");
	//$nombre = $obj->get_var("SELECT name FROM ejemplo_tabla WHERE id_ejemplo_tabla = 1");

	if(!empty($_POST)){
		$name = $_POST["name"];
		$email = $_POST["email"];
		$obj->query("INSERT INTO ejemplo_tabla (name, email) VALUES ('{$name}', '{$email}')");//Insert
	}
?>