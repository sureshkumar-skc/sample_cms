<?php
require_once("../includes/session.php");
require_once("../includes/db_connections.php");
require_once("../includes/functions.php");
confirm_logged_in();
$layout_content = "admin";
include("../includes/layouts/header.php");
?>
<div id='main'>
    <div id='navigation'>


    </div>
    <div id='page'>
        <h2>Admin menu</h2>
        <p>Welcome to the admin area.</p>
        <ul>
            <li><a href='manage_content.php'>Manage website content</a>   </li>
            <li><a href='manage_admin.php'>Manage admin users</a>   </li>
            <li><a href="modify_company_details.php">Change company name</a></li>
            <li><a href='logout.php'>Logout</a>   </li>
        </ul> 
    </div>
</div>
<?php
include("../includes/layouts/footer.php");
?>
