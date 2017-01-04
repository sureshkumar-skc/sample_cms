<?php
function redirect_to($new_location) {
	header ( "Location: " . $new_location );
	exit ();
}
function confirm_query($result_set) {
	if (! $result_set) {
		die ( "Database query failed." );
	}
}
function mysql_prep($string){
	global $connection;
	$escaped_string = mysqli_real_escape_string($connection, $string);
	return $escaped_string;
}

function form_errors($errors = array()) {
	$output = "";
	if (! empty ( $errors )) {
		$output = "<div class=\"errors\">";
		$output .= "Please fix the following errors: ";
		$output .= "<ul>";
		foreach ( $errors as $key => $error ) {
			$output .= "<li>";
			$output .= htmlentities($error);
			$output .= "</li>";
		}
		$output .= "</ul>";
		$output .= "</div>";
	}
	return $output;
}
function find_current_user(){
    if (isset($_GET ["user"])) {
      $user_id = $_GET ["user"];
      return find_user_by_id($user_id);
    }else{
        return null;
    }
}

function find_user_by_id($user_id){
    global $connection;
	$user_id_safe = mysqli_real_escape_string ( $connection, $user_id );
	$query = "SELECT * FROM admins WHERE id = " . $user_id_safe . " LIMIT 1";
	$user_set = mysqli_query ( $connection, $query );
	confirm_query ( $user_set );
	$user = mysqli_fetch_assoc ( $user_set );
	if ($user) {
		return $user;
	} else {
		return null;
	}
}
function find_all_subjects($isPublic = true) {
	global $connection;
        if($isPublic){
            $query = "SELECT * FROM subjects WHERE visible = 1 ORDER BY position ASC";   
        }else{
            $query = "SELECT * FROM subjects ORDER BY position ASC";
        }
	$subject_set = mysqli_query ( $connection, $query );
	confirm_query ( $subject_set );
	return $subject_set;
}
function find_pages_by_subject_id($subject_id, $isPublic = true) {
	global $connection;
        if($isPublic){
          $query = "SELECT * FROM pages WHERE visible = 1 AND subject_id = " . $subject_id . " ORDER BY position ASC";
        }else{
            $query = "SELECT * FROM pages WHERE subject_id = " . $subject_id . " ORDER BY position ASC";
        }
        $pages_set = mysqli_query ( $connection, $query );
	confirm_query ( $pages_set );
	return $pages_set;
}
function find_default_page_for_subject($subject_id){
    $pages_set = find_pages_by_subject_id($subject_id); 
    if($first_page = mysqli_fetch_assoc($pages_set)){
        return $first_page;
    }else{
        return null;
    }
}
function find_all_admins(){
    global $connection;
    
    $query = "SELECT * FROM admins ORDER BY username ASC";
    $user_set= mysqli_query ( $connection, $query );
    confirm_query ( $user_set );
    return $user_set; // Returns the details of all the users
}

function find_selected_items($isPublic = false) {
    global $selected_subject_details;
    global $selected_page_details;
    if (isset($_GET ["subject"])) {
        $selected_subject_details = find_subjects_by_id($_GET ["subject"]); // -> This contains the details of the currenct subject
        if ($isPublic) {
            $selected_page_details = find_default_page_for_subject($selected_subject_details["id"]);
        }
    } else if (isset($_GET ["page"])) {
        $selected_page_details = find_pages_by_id($_GET ["page"], $isPublic); // -> This contains the details of the currenct page
        $selected_subject_details = null;
    } else {
        $selected_page_details = null;
        $selected_subject_details = null;
    }
}

function find_subjects_by_id($subjet_id) {
	global $connection;
	$subjet_id_safe = mysqli_real_escape_string ( $connection, $subjet_id );
	$query = "SELECT * FROM subjects WHERE id = " . $subjet_id_safe . " LIMIT 1";
	$subject_set = mysqli_query ( $connection, $query );
	confirm_query ( $subject_set );
	$subject = mysqli_fetch_assoc ( $subject_set );
	if ($subject) {
		return $subject;
	} else {
		return null;
	}
}
function find_pages_by_id($page_id, $isPublic=true) {
    global $connection;
    $safe_page_id = mysqli_real_escape_string($connection, $page_id);
    if ($isPublic) {
         $query = "SELECT * FROM pages WHERE id = " . $safe_page_id . " AND visible = 1 ORDER BY position ASC";
    } else {
        $query = "SELECT * FROM pages WHERE id = " . $safe_page_id . " ORDER BY position ASC";
    }
    $pages_set = mysqli_query($connection, $query);
    confirm_query($pages_set);
    $page = mysqli_fetch_assoc($pages_set);
    if ($page) {
        return $page;
    } else {
        return null;
    }
}

function close_database_connection($connection) {
// 	echo "Closing the database";
    if (isset($connection)) {
        mysqli_close($connection);
    }
}

function navigation($subject_details, $page_details) {
	$subject_id = $subject_details ["id"];
	$page_id = $page_details ["id"];
	$output = "<ul class=\"subjects\">";
	$subject_set = find_all_subjects (false);
	while ( $subject = mysqli_fetch_assoc ( $subject_set ) ) {
		$output .= "<li ";
		// echo "subject_id: ".$subject_id;
		// echo "</br>";
		// echo "subject[\"id\"]: ".$subject["id"];
		// echo "</br>";
		if ($subject_id && $subject_id == $subject ["id"]) {
			$output .= "class=\"selected\"";
		}
		$output .= ">  <a href=\"manage_content.php?subject=";
		$output .= urlencode ( $subject ["id"] );
		$output .= "\">";
		$output .= htmlentities($subject ["menu_name"]);
		$output .= "</a></li>";
		$pages_set = find_pages_by_subject_id( $subject ["id"], FALSE );
		while ( $page = mysqli_fetch_assoc ( $pages_set ) ) {
			$output .= "<ul class=\"pages\" > <li ";
			
			if ($page_id && $page_id == $page ["id"]) {
				$output .= "class=\"selected\"";
			}
			$output .= "><a href=\"manage_content.php?page=";
			$output .= urlencode ( $page ["id"] );
			$output .= "\">";
			$output .= htmlentities($page ["menu_name"]);
			$output .= "</a></li> </ul>";
		}
		mysqli_free_result ( $pages_set );
	}
	mysqli_free_result ( $subject_set );
	$output .= "</ul>";
	return $output;
}
function navigation_public($subject_details, $page_details) {
	$subject_id = $subject_details ["id"];
	$page_id = $page_details ["id"];
	$output = "<ul class=\"subjects\">";
	$subject_set = find_all_subjects ();
	while ( $subject = mysqli_fetch_assoc ( $subject_set ) ) {
		$output .= "<li ";
		// echo "subject_id: ".$subject_id;
		// echo "</br>";
		// echo "subject[\"id\"]: ".$subject["id"];
		// echo "</br>";
		if ($subject_id && $subject_id == $subject ["id"]) {
			$output .= "class=\"selected\"";
		}
		$output .= ">  <a href=\"index.php?subject=";
		$output .= urlencode ( $subject ["id"] );
		$output .= "\">";
		$output .= htmlentities($subject ["menu_name"]);
		$output .= "</a></li>";
                if($subject_id == $subject ["id"] || $subject ["id"] == $page_details["subject_id"]){
		$pages_set = find_pages_by_subject_id ( $subject ["id"] );
		while ( $page = mysqli_fetch_assoc ( $pages_set ) ) {
			$output .= "<ul class=\"pages\" > <li ";
			
			if ($page_id && $page_id == $page ["id"]) {
				$output .= "class=\"selected\"";
			}
                        $output .= "><a href=\"index.php?page=";
			$output .= urlencode ( $page ["id"] );
			$output .= "\">";
			$output .= htmlentities($page ["menu_name"]);
			$output .= "</a></li> ";
                        $output .= "</ul>";
		}
                mysqli_free_result ( $pages_set );
                }
    
	}
	mysqli_free_result ( $subject_set );
	$output .= "</ul>";
	return $output;
}

?>