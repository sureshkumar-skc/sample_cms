<?php
require_once("../includes/functions.php");
if (!isset($layout_content)) {
    $layout_content = "public";
}
?>
<!DOCTYPE html>
<html lang=en>
    <head>
        <title><?php echo get_company_name() ?> <?php
            if ($layout_content == "admin") {
                echo ": Admin";
            }
            ?></title>
        <link href='stylesheets/public.css' media='all' rel='stylesheet' type='text/css' /> 
    </head>
    <body>
        <div id='header'> 
            <div class="companyname">
                <h1><?php echo get_company_name();  ?><?php
                if ($layout_content == "admin") {
                    echo ": Admin";
                }
                ?></h1>
            </div>
            <div class="editcompanyname">
                <?php
                 if ($layout_content == "admin") {
                     ?>
                <a href="modify_company_details.php"> Edit </a>
                <?php
                }
                ?>
            </div>
        </div>
