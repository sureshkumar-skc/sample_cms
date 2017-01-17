<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
confirm_logged_in();
$layout_content = "admin";
include ("../includes/layouts/header.php");
?>
<div id='main'>
    <div id='navigation'>
         <a href="admin.php">&laquo; Main menu</a>
    </div>
    <div id='users'>
        <h2>Manage Users</h2>
        <?php echo message(); ?>
        <?php
        $admin_users = find_all_admins();
        if (isset($admin_users) && mysqli_num_rows($admin_users) > 0) {
            ?>
            <div >
                <table>
                    <tr >
                        <th>Username</th>
                        <th colspan="2">Actions</th>
                    </tr>
                    <?php
                    while ($user = mysqli_fetch_assoc($admin_users)) {
                        ?>
                        <tr>
                            <td><?php echo htmlentities($user["username"]); ?> </td>

                            <td><a href=edit_admin.php?user=<?php echo htmlentities($user["id"]); ?> >Edit</a> &nbsp;
                                <a href=delete_admin.php?user=<?php echo $user["id"]; ?> onclick="return confirm('Are you sure to delete the user?')" >Delete</a></td> 

                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <?php
        } else {
            echo "User not present";
        }
        ?>
        <br>
        <br>
        <a href="create_admin.php">+ Add User</a>
    </div>
</div>

<?php
include ("../includes/layouts/footer.php");
?>