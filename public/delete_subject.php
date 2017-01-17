<?php

require_once ("../includes/session.php");
require_once ("../includes/functions.php");
require_once ("../includes/db_connections.php");
confirm_logged_in();
?>
<?php

$selected_subject_details = find_subjects_by_id($_GET["subject"]);
if (!$selected_subject_details) {
    redirect_to("manage_content.php");
} else {
    $id = $selected_subject_details["id"];
    $menu_name_delete = $selected_subject_details["menu_name"];
    $pages_set = find_pages_by_subject_id($id, false);
    if (mysqli_num_rows($pages_set) > 0) {
        $_SESSION ["message"] = "Delete all the pages in " . $menu_name_delete;
        redirect_to("manage_content.php?subject=" . $id);
    }
    $position = $selected_subject_details["position"];
    $position_swapping = subject_position_decrement_swift($position);
    $query = "DELETE FROM subjects WHERE id=" . $id;
    $result = mysqli_query($connection, $query);
    if ($position_swapping && $result && mysqli_affected_rows($connection)) {
        $_SESSION ["message"] = "Subject " . $menu_name_delete . " is deleted";
        redirect_to("manage_content.php");
    } else {
        $_SESSION ["message"] = "Subject Deletion failed";
        redirect_to("manage_content.php?subject=" . $id);
    }
}
?>
