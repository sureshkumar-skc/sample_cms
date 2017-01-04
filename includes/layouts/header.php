<?php 
if(!isset($layout_content)){
$layout_content = "public";     
}
?>
<!DOCTYPE html>
<html lang=en>
<head>
<title>Widget corp <?php if($layout_content == "admin"){ echo ": Admin";} ?></title>
<link href='stylesheets/public.css' media='all' rel='stylesheet' type='text/css' /> 
</head>
<body>
<div id='header'> 
    <h1>Widget Corp <?php if($layout_content == "admin"){ echo ": Admin";} ?></h1>
</div>