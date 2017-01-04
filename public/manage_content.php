<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
$layout_content = "admin";
include ("../includes/layouts/header.php");
?>
<?php find_selected_items(); ?>
<div id='main'>
    <div id='navigation'>
        <a href="admin.php">&laquo; Main menu</a>
        <?php echo navigation($selected_subject_details, $selected_page_details); ?>
        <br> <a href="new_subject.php">+ Add a subject</a>
    </div>
    <div id='page'>
        <h2>Manage Content</h2>
        <?php echo message(); ?>
        <!--  <p>Welcome to the manage area.</p> -->
        <?php if ($selected_subject_details) { ?>
            <h3>Manage Subject</h3>
            <!-- <?php
// 									echo "selected_subject_details </br>";
// 									print_r ( $selected_subject_details );
// 									
            ?> -->

            <br/>
            Menu Name: <?php echo htmlentities($selected_subject_details["menu_name"]) ?><br>
            Position: <?php echo $selected_subject_details["position"] ?><br>
            Visible: <?php echo $selected_subject_details["visible"] == 1 ? "Yes" : "No" ?><br><br>
            <a href="edit_subject.php?subject=<?php echo urlencode($selected_subject_details["id"]); ?>"> Edit Subject</a> 
            &nbsp; &nbsp; 
            <a href="delete_subject.php?subject=<?php echo urlencode($selected_subject_details["id"]); ?> " onclick="return confirm('Are you sure to delete?')"> Delete Subject</a>
            <br> <br>
            <h2>Pages in <?php echo htmlentities($selected_subject_details["menu_name"]) ?></h2>
            <ul>
                <?php
                $pages_set = find_pages_by_subject_id($selected_subject_details["id"], false);
                while ($page = mysqli_fetch_assoc($pages_set)) {
                    ?>

                    <li> <a href="edit_page.php?page=<?php echo htmlentities($page["id"]); ?>"> <?php echo htmlentities($page["menu_name"]); ?></a></li>

                    <?php
                }
                ?>
            </ul> <br>
            <a href="new_page.php?subject=<?php echo htmlentities($selected_subject_details["id"]) ?>">+ Add a page in <?php echo htmlentities($selected_subject_details["menu_name"]) ?></a>

        <?php } else if ($selected_page_details) { ?>
            <h3>Manage Page</h3>
            <!--  <?php
//  									echo "selected_page_details </br>";
//  									print_r ( $selected_page_details );
// 									echo "<br>";
            ?>  -->
            Menu Name: <?php echo htmlentities($selected_page_details["menu_name"]) ?><br>
            Position: <?php echo $selected_page_details["position"] ?><br>
            Visible: <?php echo $selected_page_details["visible"] == 1 ? "Yes" : "No" ?><br>
            Content: <br> 
            <div class="view-content"><?php echo htmlentities($selected_page_details["content"]); ?></div>
            <a href="edit_page.php?page=<?php echo urlencode($selected_page_details["id"]); ?>">Edit page</a>
            &nbsp; &nbsp;
            <a href="delete_page.php?page=<?php echo urlencode($selected_page_details["id"]); ?>" onclick="return confirm('Are you sure to delete?')">Delete page</a>

        <?php } else { ?>
            <h3>Please select a subject or page.</h3>
        <?php } ?> 
    </div>
</div>

<?php
include ("../includes/layouts/footer.php");
?>