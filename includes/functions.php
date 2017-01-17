<?php

function redirect_to($new_location) {
    header("Location: " . $new_location);
    exit();
}

function confirm_query($result_set) {
    if (!$result_set) {
        die("Database query failed.");
    }
}

function mysql_prep($string) {
    global $connection;
    $escaped_string = mysqli_real_escape_string($connection, $string);
    return $escaped_string;
}

function form_errors($errors = array()) {
    $output = "";
    if (!empty($errors)) {
        $output = "<div class=\"errors\">";
        $output .= "Please fix the following errors: ";
        $output .= "<ul>";
        foreach ($errors as $key => $error) {
            $output .= "<li>";
            $output .= htmlentities($error);
            $output .= "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}

function find_current_user() {
    if (isset($_GET ["user"])) {
        $user_id = $_GET ["user"];
        return find_user_by_id($user_id);
    } else {
        return null;
    }
}

function find_user_by_id($user_id) {
    global $connection;
    $user_id_safe = mysqli_real_escape_string($connection, $user_id);
    $query = "SELECT * FROM admins WHERE id = " . $user_id_safe . " LIMIT 1";
    $user_set = mysqli_query($connection, $query);
    confirm_query($user_set);
    $user = mysqli_fetch_assoc($user_set);
    if ($user) {
        return $user;
    } else {
        return null;
    }
}

function find_user_by_username($username) {
    global $connection;
    $user_name_safe = mysqli_real_escape_string($connection, $username);
    $query = "SELECT * FROM admins WHERE username = '" . $user_name_safe . "' LIMIT 1";
    $user_set = mysqli_query($connection, $query);
    confirm_query($user_set);
    $user = mysqli_fetch_assoc($user_set);
    if ($user) {
        return $user;
    } else {
        return null;
    }
}

function find_all_subjects($isPublic = true) {
    global $connection;
    if ($isPublic) {
        $query = "SELECT * FROM subjects WHERE visible = 1 ORDER BY position ASC";
    } else {
        $query = "SELECT * FROM subjects ORDER BY position ASC";
    }
    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set);
    return $subject_set;
}

function find_pages_by_subject_id($subject_id, $isPublic = true) {
    global $connection;
    if ($isPublic) {
        $query = "SELECT * FROM pages WHERE visible = 1 AND subject_id = " . $subject_id . " ORDER BY position ASC";
    } else {
        $query = "SELECT * FROM pages WHERE subject_id = " . $subject_id . " ORDER BY position ASC";
    }
    $pages_set = mysqli_query($connection, $query);
    confirm_query($pages_set);
    return $pages_set;
}

function find_default_page_for_subject($subject_id) {
    $pages_set = find_pages_by_subject_id($subject_id);
    if ($first_page = mysqli_fetch_assoc($pages_set)) {
        return $first_page;
    } else {
        return null;
    }
}

function find_all_admins() {
    global $connection;

    $query = "SELECT * FROM admins ORDER BY username ASC";
    $user_set = mysqli_query($connection, $query);
    confirm_query($user_set);
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

function subject_position_swap($old_position, $new_position) {
    global $connection;
    if ($old_position - $new_position > 0) {
        $query_swap_position = "UPDATE subjects SET position = position +1 WHERE position >= $new_position AND position < $old_position";
    } else if ($old_position - $new_position < 0) {
        $query_swap_position = "UPDATE subjects SET position = position -1 WHERE position > $old_position AND position <=$new_position";
    } else {
        return true;
        // If the old position and the new position is same then no need to shift the position.
    }
    $result = mysqli_query($connection, $query_swap_position);
    if ($result && mysqli_affected_rows($connection) >= 0) {
        return true;
    }
    return false;
}
function subject_position_increment_swift($position){
    global $connection;
    $query_swap_position = "UPDATE subjects SET position = position +1 WHERE position >= $position";
    $result = mysqli_query($connection, $query_swap_position);
    if ($result && mysqli_affected_rows($connection) >= 0) {
        return true;
    }
     return false;
}
function subject_position_decrement_swift($position){
    global $connection;
    $query_swap_position = "UPDATE subjects SET position = position -1 WHERE position >= $position";
    $result = mysqli_query($connection, $query_swap_position);
    if ($result && mysqli_affected_rows($connection) >= 0) {
        return true;
    }
     return false;
}
function page_position_swap($old_position, $new_position, $subject_id){
     global $connection;
    if ($old_position - $new_position > 0) {
        $query_swap_position = "UPDATE pages SET position = position +1 WHERE subject_id = $subject_id AND position >= $new_position AND position < $old_position";
    } else if ($old_position - $new_position < 0) {
        $query_swap_position = "UPDATE pages SET position = position -1 WHERE subject_id = $subject_id AND position > $old_position AND position <=$new_position";
    } else {
        return true;
        // If the old position and the new position is same then no need to shift the position.
    }
    $result = mysqli_query($connection, $query_swap_position);
    if ($result && mysqli_affected_rows($connection) >= 0) {
        return true;
    }
    return false;
}
function page_position_increment_swift($position, $subject_id){
    global $connection;
    $query_swap_position = "UPDATE pages SET position = position +1 WHERE subject_id = $subject_id AND position >= $position";
    $result = mysqli_query($connection, $query_swap_position);
    if ($result && mysqli_affected_rows($connection) >= 0) {
        return true;
    }
     return false;
}
function page_position_decrement_swift($position, $subject_id){
    global $connection;
    $query_swap_position = "UPDATE pages SET position = position -1 WHERE subject_id = $subject_id AND position >= $position";
    $result = mysqli_query($connection, $query_swap_position);
    if ($result && mysqli_affected_rows($connection) >= 0) {
        return true;
    }
     return false;
}
function find_subjects_by_id($subjet_id) {
    global $connection;
    $subjet_id_safe = mysqli_real_escape_string($connection, $subjet_id);
    $query = "SELECT * FROM subjects WHERE id = " . $subjet_id_safe . " LIMIT 1";
    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set);
    $subject = mysqli_fetch_assoc($subject_set);
    if ($subject) {
        return $subject;
    } else {
        return null;
    }
}

function find_pages_by_id($page_id, $isPublic = true) {
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

function password_encrypt($password) {
//    $hash_format = "$2y$10$"; //Tel PHP to use blowfish with a 'cost' of 10
//    $salt_length = 22; //Blowfish salts lenght should be 22-characters or more 
//    
//    $salt = generate_salt($salt_length);
//    $format_and_salt = $hash_format . $salt;
//    $hash = crypt($password, $format_and_salt);
    $cost = array("cost" => 10);
    $hash = password_hash($password, PASSWORD_BCRYPT, $cost);

    return $hash;
}

function generate_salt($salt_length) {
    //md5 returns 32 character
    $unique_random_string = md5(uniqid(mt_rand(), true));

    // valid character for salt are [a-zA-Z0-9./]
    $base64_string = base64_encode($unique_random_string);

    // Removing + to . 
    $modified_base64_string = str_replace("+", ".", $base64_string);

    //Truncating to required character 
    $salt = substr($modified_base64_string, 0, $salt_length);
    return $salt;
}

function passwod_check($password, $existing_hash) {
    $hash = crypt($password, $existing_hash);
    if ($hash === $existing_hash) {
        return true;
    } else {
        return false;
    }
}

function close_database_connection($connection) {
    if (isset($connection)) {
        mysqli_close($connection);
    }
}

function logged_in() {
    return isset($_SESSION["admin_user"]);
}

function confirm_logged_in() {
    if (!logged_in()) {
        redirect_to("login.php");
    }
}

function get_company_name() {
    global $connection;
    if (empty($_SESSION["company_name"])) {
        $query = "SELECT * FROM company_details WHERE id=1 LIMIT 1";
        $result = mysqli_query($connection, $query);
        confirm_query($result);
        $company_details = mysqli_fetch_assoc($result);
        $company_name = $company_details["company_name"];
        $_SESSION["company_name"] = $company_name;
    }
    return $_SESSION["company_name"];
}

function get_start_date() {
    global $connection;
    if (empty($_SESSION["start_date"])) {
        $query = "SELECT * FROM company_details WHERE id=1 LIMIT 1";
        $result = mysqli_query($connection, $query);
        confirm_query($result);
        $company_details = mysqli_fetch_assoc($result);
        $company_name = $company_details["year_start"];
        $_SESSION["start_date"] = $company_name;
    }
    return $_SESSION["start_date"];
}

function navigation($subject_details, $page_details) {
    $subject_id = $subject_details ["id"];
    $page_id = $page_details ["id"];
    $output = "<ul class=\"subjects\">";
    $subject_set = find_all_subjects(false);
    while ($subject = mysqli_fetch_assoc($subject_set)) {
        $output .= "<li ";
        if ($subject_id && $subject_id == $subject ["id"]) {
            $output .= "class=\"selected\"";
        }
        $output .= ">  <a href=\"manage_content.php?subject=";
        $output .= urlencode($subject ["id"]);
        $output .= "\">";
        $output .= htmlentities($subject ["menu_name"]);
        $output .= "</a></li>";
        $pages_set = find_pages_by_subject_id($subject ["id"], FALSE);
        while ($page = mysqli_fetch_assoc($pages_set)) {
            $output .= "<ul class=\"pages\" > <li ";

            if ($page_id && $page_id == $page ["id"]) {
                $output .= "class=\"selected\"";
            }
            $output .= "><a href=\"manage_content.php?page=";
            $output .= urlencode($page ["id"]);
            $output .= "\">";
            $output .= htmlentities($page ["menu_name"]);
            $output .= "</a></li> </ul>";
        }
        mysqli_free_result($pages_set);
    }
    mysqli_free_result($subject_set);
    $output .= "</ul>";
    return $output;
}

function navigation_public($subject_details, $page_details) {
    $subject_id = $subject_details ["id"];
    $page_id = $page_details ["id"];
    $output = "<ul class=\"subjects\">";
    $subject_set = find_all_subjects();
    while ($subject = mysqli_fetch_assoc($subject_set)) {
        $output .= "<li ";
        // echo "subject_id: ".$subject_id;
        // echo "</br>";
        // echo "subject[\"id\"]: ".$subject["id"];
        // echo "</br>";
        if ($subject_id && $subject_id == $subject ["id"]) {
            $output .= "class=\"selected\"";
        }
        $output .= ">  <a href=\"index.php?subject=";
        $output .= urlencode($subject ["id"]);
        $output .= "\">";
        $output .= htmlentities($subject ["menu_name"]);
        $output .= "</a></li>";
        if ($subject_id == $subject ["id"] || $subject ["id"] == $page_details["subject_id"]) {
            $pages_set = find_pages_by_subject_id($subject ["id"]);
            while ($page = mysqli_fetch_assoc($pages_set)) {
                $output .= "<ul class=\"pages\" > <li ";

                if ($page_id && $page_id == $page ["id"]) {
                    $output .= "class=\"selected\"";
                }
                $output .= "><a href=\"index.php?page=";
                $output .= urlencode($page ["id"]);
                $output .= "\">";
                $output .= htmlentities($page ["menu_name"]);
                $output .= "</a></li> ";
                $output .= "</ul>";
            }
            mysqli_free_result($pages_set);
        }
    }
    mysqli_free_result($subject_set);
    $output .= "</ul>";
    return $output;
}

?>