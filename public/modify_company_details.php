<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
require_once ("../includes/validation_functions.php");
confirm_logged_in();
$layout_content = "admin";
include ("../includes/layouts/header.php");
?>
<?php
if (isset($_POST ["submit"])) {
    // Form processing
    $company_name = $_POST["company_name"];
    $required_fields = array("company_name");
    validate_presences($required_fields);
    validate_max_length(array("company_name" => 30));
    if (empty($errors)) {
        $company_name = mysql_prep($company_name); 
        $query = "UPDATE company_details SET company_name='$company_name' WHERE id = 1";
        $result = mysqli_query($connection, $query);
        if($result){
		$_SESSION["message"] = "Company name updated";
                $_SESSION["company_name"] = null;
		redirect_to("manage_content.php");
	}else {
		$_SESSION["message"] = "Company name updation failed";
	}
    }else{
         // errors were not empty, present
     }
}else{
    // Not a post request
}
?>


<div id='main'>
    <div id='navigation'>
        <a href="admin.php">&laquo; Main menu</a>
    </div>
    <div id="page">
        <?php echo message(); ?>
       
       
        <?php echo form_errors($errors); ?>

        <h2>Edit company details</h2>
        <form action="modify_company_details.php" name="company_details" method="post">
            <p> Company Name:  <input type="text" name="company_name" value="<?php echo get_company_name(); ?>" > </p>
            <!-- <p> Start Date: <input type="date" value="<?php //echo get_start_date(); ?>" name="start_date" max="<?php // echo date("Y-m-d"); ?>" /> </p> -->
            <p>
                <input class="save" type="submit" name="submit"
                       value="Update company details" />
            </p>
        </form>
    </div>
</div>

<?php
include("../includes/layouts/footer.php");
?>