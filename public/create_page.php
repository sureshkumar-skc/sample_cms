<?php session_start(); ?>
<?php

require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
require_once ("../includes/validation_functions.php");
?>
<?php

if (isset ( $_POST ["submit"] )) {
	$menu_name = $_POST["menu_name"];
	$position = (int) $_POST["position"];
	if(!isset($_POST["visible"])){
		$_POST["visible"] = null;
	}
	$visible = (int) $_POST["visible"];
	$content = $_POST["content"];
	$subject_id = (int) $_POST["subject_id"];
	
	$required_fields = array("menu_name", "position", "visible", "content");
	validate_presences($required_fields);
	
	validate_max_length(array("menu_name" => 30));
	if(!empty($errors)){
		$_SESSION["form_errors"] = $errors;
		redirect_to("new_page.php?subject=".$subject_id);
	}
	$menu_name = mysql_prep($menu_name);
	$content = mysql_prep($content);
	
	$query = "INSERT INTO pages(subject_id, menu_name, position, visible, content) VALUES('".$subject_id."', '".$menu_name."', '".$position."', '".$visible."', '".$content."')";
// 	file_put_contents("Testlog.txt", print_r("\n query: ".$query, true), FILE_APPEND);
	$result = mysqli_query($connection, $query);
// 	file_put_contents("Testlog.txt", print_r("\n result: ".$result, true), FILE_APPEND);
	if($result){
		$_SESSION["message"] = "Page created";
		redirect_to("manage_content.php".$subject_id);
	}else {
		$_SESSION["message"] = "Page creation failed";
		redirect_to("new_page.php");
	}
	
} else {
	redirect_to("new_page.php");
}

?>