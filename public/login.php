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
//    validate_max_length(array("username" => 25, "password" => 25));
    if (empty($errors)) {
        $user_present = find_user_by_username($username);
        if ($user_present && password_verify($password, $user_present["hashed_password"])) {
            $_SESSION["admin_user"] = $user_present["id"];
            $_SESSION["username"] = $user_present["username"];
            redirect_to("admin.php");
        } else {
            $errors = array("Username/Password Invalid");
        }
    }
} else {
    // Redirecting to the login page which is written below
}
?>
<div id='main'>
    <div id='navigation'>
    </div>
    <div id="users" class="login">
        <?php echo message(); ?>
        <?php echo form_errors($errors); ?>

        <h2>Login to widget corp</h2>
        <form action="login.php" method="post">
            <p>

                Username: <input type="text" name="username" value="<?php
                if (isset($username)) {
                    echo $username;
                }
                ?>" />
            </p>
            <p>
                Password: <input type="password" name="password" value="" />
            </p>
            <p>
                <input class="save" type="submit" name="submit"
                       value="Login" />
            </p>
        </form>
    </div>
</div>		
<?php
include("../includes/layouts/footer.php");
?>