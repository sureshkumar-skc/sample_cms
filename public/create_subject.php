<?php session_start(); ?>
<?php
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
require_once ("../includes/validation_functions.php");
confirm_logged_in();
?>
<?php
if (isset ( $_POST ["submit"] )) {
	//Form processing 
	$menu_name = $_POST["menu_name"];
	$position = (int) $_POST["position"]; 
	if(!isset($_POST["visible"])){
		$_POST["visible"] = null;
	}
	$visible = (int) $_POST["visible"];
	$required_fields = array("menu_name", "position", "visible");
	validate_presences($required_fields);
	
	$field_with_max_lengths = array("menu_name" => 30);
	validate_max_length($field_with_max_lengths);
	
	if (!empty($errors)){
		$_SESSION["form_errors"] = $errors;
		redirect_to("new_subject.php");
	}
        $position_swift = subject_position_increment_swift($position); 
	$menu_name = mysql_prep($menu_name);
	$query = "INSERT INTO subjects (menu_name, position, visible) VALUES ('".$menu_name."', '".$position."', '".$visible."')";
	
	$result = mysqli_query($connection, $query);
	
	if($position_swift && $result){
		$_SESSION["message"] = "Subject created";
		redirect_to("manage_content.php");
	}else {
		$_SESSION["message"] = "Subject creation failed";
		redirect_to("new_subject.php");
	}
} else {
	redirect_to("new_subject.php");
}

?>