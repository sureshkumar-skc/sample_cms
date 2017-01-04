<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
require_once ("../includes/validation_functions.php");
include ("../includes/layouts/header.php");
?>
<?php
if (isset($_POST ["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $id = $_POST["userid"];
    $required_fields = array("username", "password");
    validate_presences($required_fields);
    validate_max_length(array("username" => 25, "password" => 25));
    if (!empty($errors)) {
        $_SESSION["form_errors"] = $errors;
        //This will redirect to the same page
    } else {
        $username = mysql_prep($username);
        $password = mysql_prep($password); 

        $query = "UPDATE admins SET username = '".$username."', hashed_password = '".$username."' WHERE id = ".$id;

        $result = mysqli_query($connection, $query);
        if ($result) {
            $_SESSION["message"] = "User updated";
            redirect_to("manage_admin.php");
        } else {
            $_SESSION["message"] = "User updation failed";
//            redirect_to("edit_admin.php"); // This will redirect to the same page
        }
    }
} else {
    
}
?>
<div id='main'>
    <div id='navigation'>
    </div>
    <div id='users'>
        <?php echo message(); ?>
        <?php $errors = errors(); ?>
        <?php echo form_errors($errors); ?>
        <?php $current_user = find_current_user(); ?>
        <h2>Edit User <?php if(isset($current_user)) {echo ': '.$current_user["username"];} ?></h2>
        <form action="edit_admin.php" method="post">
            <input type="hidden" name="userid" value="<?php echo $current_user["id"]?>"/> 
            <p>
                Username: <input type="text" name="username" value="<?php if(isset($username)) {echo $username;} else if(isset($current_user)) {echo $current_user["username"];}?>" />
            </p>
            <p>
                Password: <input type="password" name="password" value="" />
            </p>
            <p>
                <input class="save" type="submit" name="submit"
                       value="Update User" />
                &nbsp; &nbsp;
                <a href="manage_admin.php"> Cancel </a>
            </p>
        </form>
    </div>
</div>			