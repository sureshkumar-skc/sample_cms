<?php

require_once ("../includes/session.php");
require_once ("../includes/functions.php");
require_once ("../includes/db_connections.php");
confirm_logged_in();
?>
<?php

$selected_page_details = find_pages_by_id($_GET["page"], false);
if (!$selected_page_details) {
    redirect_to("manage_content.php");
} else {
    $id = $selected_page_details["id"];
    $position = $selected_page_details["position"];
    $subject_id = $selected_page_details["subject_id"];
    $menu_name_delete = $selected_page_details["menu_name"];
    $position_swift = page_position_decrement_swift($position, $subject_id);
    
    $query = "DELETE FROM pages WHERE id=" . $id;
    $result = mysqli_query($connection, $query);
    if ($position_swift && $result && mysqli_affected_rows($connection)) {
        $_SESSION ["message"] = "Page " . $menu_name_delete . " is deleted";
        redirect_to("manage_content.php");
    } else {
        $_SESSION ["message"] = "Page Deletion failed";
        redirect_to("manage_content.php?page=" . $id);
    }
}
?>
