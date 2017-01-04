<?php
require_once("../includes/db_connections.php");
require_once("../includes/functions.php");
$layout_content = "admin"; 
include("../includes/layouts/header.php");
?>
<?php
$query = "SELECT * FROM subjects WHERE visible = 1 ORDER BY position ASC";
$result = mysqli_query($connection, $query);

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
<li><a href='logout.php'>Logout</a>   </li>
</ul> 
</div>
</div>
<?php
mysqli_free_result($result);
?>
<?php
include("../includes/layouts/footer.php");
?>
