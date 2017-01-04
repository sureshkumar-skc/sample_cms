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
    $required_fields = array("username", "password");
    validate_presences($required_fields);
    validate_max_length(array("username" => 25, "password" => 25));
    if (!empty($errors)) {
        $_SESSION["form_errors"] = $errors;
        //This will redirect to the same page
    } else {
        $username = mysql_prep($username);
        $password = mysql_prep($password); 

        $query = "INSERT INTO admins (username, hashed_password) VALUES ('" . $username . "', '" . $password . "')";

        $result = mysqli_query($connection, $query);
        if ($result) {
            $_SESSION["message"] = "User created";
            redirect_to("manage_admin.php");
        } else {
            $_SESSION["message"] = "User creation failed";
//            redirect_to("create_admin.php"); // This will redirect to the same page
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
        <?php $errors = errors();
        ?>

        <?php echo form_errors($errors); ?>

        <h2>Create User</h2>
        <form action="create_admin.php" method="post">
            <p>
                                
                Username: <input type="text" name="username" value="<?php if(isset($username)) echo $username; ?>" />
            </p>
            <p>
                Password: <input type="password" name="password" value="" />
            </p>
            <p>
                <input class="save" type="submit" name="submit"
                       value="Create User" />
                &nbsp; &nbsp;
                <a href="manage_admin.php"> Cancel </a>
            </p>
        </form>
    </div>
</div>			