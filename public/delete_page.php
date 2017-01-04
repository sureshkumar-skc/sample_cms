<?php

require_once ("../includes/session.php");
require_once ("../includes/functions.php");
require_once ("../includes/db_connections.php");
?>
<?php

$selected_page_details = find_pages_by_id($_GET["page"], false);
if (!$selected_page_details) {
    redirect_to("manage_content.php");
} else {
    $id = $selected_page_details["id"];
    $menu_name_delete = $selected_page_details["menu_name"];
//	$pages_set = find_pages_by_subject_id($id, false);
//	if(mysqli_num_rows($pages_set) > 0){
//		$_SESSION ["message"] = "Delete all the pages in ".$menu_name_delete;
//		redirect_to ( "manage_content.php?subject=".$id );
//	}
    $query = "DELETE FROM pages WHERE id=" . $id;
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_affected_rows($connection)) {
        $_SESSION ["message"] = "Page " . $menu_name_delete . " is deleted";
        redirect_to("manage_content.php");
    } else {
        $_SESSION ["message"] = "Page Deletion failed";
        redirect_to("manage_content.php?page=" . $id);
    }
}
?>
