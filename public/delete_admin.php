<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");

$current_user = find_user_by_id($_GET["user"]);
if ($current_user) {
    $id = $current_user["id"];
    $username_delete = $current_user["username"];
    $query = "DELETE FROM admins WHERE id=" . $id;
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_affected_rows($connection)) {
        $_SESSION ["message"] = "User " . $username_delete . " is deleted";
        redirect_to("manage_admin.php");
    } else {
        $_SESSION ["message"] = "User Deletion failed";
        redirect_to("manage_admin.php");
    }
} else {
    redirect_to("manage_admin.php");
}