<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
require_once ("../includes/validation_functions.php");
?>
<?php find_selected_items(); ?>
<?php
if (!$selected_subject_details) {
    redirect_to("manage_content.php");
}
?>
<?php
if (isset($_POST ["submit"])) {
    // Form processing

    $required_fields = array(
        "menu_name",
        "position",
        "visible"
    );
    validate_presences($required_fields);

    $field_with_max_lengths = array(
        "menu_name" => 30
    );
    validate_max_length($field_with_max_lengths);
    if (empty($errors)) {
        $id = $selected_subject_details["id"];
        $menu_name = $_POST ["menu_name"];
        $menu_name = mysql_prep($menu_name);
        $position = (int) $_POST ["position"];
        if (!isset($_POST ["visible"])) {
            $_POST ["visible"] = null;
        }
        $visible = (int) $_POST ["visible"];
        $query = "UPDATE subjects SET menu_name='" . $menu_name . "', position=" . $position . ", visible=" . $visible . " WHERE id=" . $id . " LIMIT 1";
        $result = mysqli_query($connection, $query);
        if ($result && mysqli_affected_rows($connection) >= 0) {
            $_SESSION ["message"] = "Subject Updated";
            redirect_to("manage_content.php");
        } else {
            $message = "Subject Updation failed";
        }
    }
} else {
    // Edit subject page is down there
}
?>
<?php $layout_content = "admin" ?>
<?php include ("../includes/layouts/header.php"); ?>
<div id='main'>
    <div id='navigation'>
        <?php echo navigation($selected_subject_details, $selected_page_details); ?>
    </div>
    <div id='page'>
        <?php
        if (!empty($message)) {
            echo "<div class=\"message\">" . htmlentities($message) . " </div>";
        }
        ?>
        <?php echo form_errors($errors); ?> <!-- -> No need to take from the session -->

        <h2>Edit Subject: <?php echo htmlentities($selected_subject_details["menu_name"]); ?></h2>
        <form
            action="edit_subject.php?subject=<?php echo $selected_subject_details["id"]; ?>"
            method="post">
            <p>
                Menu name: <input type="text" name="menu_name"
                                  value="<?php echo htmlentities($selected_subject_details["menu_name"]); ?>" />
            </p>
            <p>
                Position: <select name="position">
                    <?php
                    $subject_set = find_all_subjects(false);
                    $subject_count = mysqli_num_rows($subject_set);
                    for ($count = 1; $count <= $subject_count; $count ++) {
                        echo "<option value=" . $count . " ";
                        if ($selected_subject_details ["position"] == $count) {
                            echo "selected";
                        }
                        echo ">" . $count . "</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                Visible: <input type="radio" value="0" name="visible"
                                <?php if ($selected_subject_details["visible"] == 0) echo " checked"; ?> />
                No &nbsp; <input type="radio" value="1" name="visible"
                                 <?php if ($selected_subject_details["visible"] == 1) echo " checked"; ?> />
                Yes
            </p>
            <input class="save" type="submit" name="submit"
                   value="Update Subject" />
            &nbsp; &nbsp;
            <a href="manage_content.php?subject=<?php echo $selected_subject_details["id"]; ?>">Cancel</a>
        </form>
    </div>
</div>

<?php
include ("../includes/layouts/footer.php");
?>