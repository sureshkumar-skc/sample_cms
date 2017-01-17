<?php
require_once ("../includes/session.php");
require_once ("../includes/functions.php");
?>
    
<?php
    
$_SESSION["admin_user"] = null;
$_SESSION["username"] = null;
$_SESSION["start_date"] = null;
$_SESSION["company_name"] = null;
redirect_to("login.php");
?>