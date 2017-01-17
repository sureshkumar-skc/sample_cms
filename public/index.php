<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
$layout_content = "public"; 
include ("../includes/layouts/header.php");
?>
<?php find_selected_items(true); ?>
<div id='main'>
    <div id='navigation'>
       <?php echo navigation_public($selected_subject_details, $selected_page_details); ?>
    </div>
    <div id='page'>
        <h2>Public Content</h2>
		<?php	echo message();	?>
        
        <?php if($selected_page_details) {?>
        <h3>Manage Page</h3>
        Menu Name: <?php echo htmlentities($selected_page_details["menu_name"])?><br>
        Content: <br> 
        <div class="view-content"><?php echo nl2br(htmlentities($selected_page_details["content"])); ?></div>
            
        <?php } else { ?>
        <h3>Welcome !!</h3>
        <p>Please select a subject or page.</p>
        <?php } ?> 
    </div>
</div>

<?php
include ("../includes/layouts/footer.php");
?>