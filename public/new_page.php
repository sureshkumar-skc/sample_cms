<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connections.php");
require_once ("../includes/functions.php");
include ("../includes/layouts/header.php");
?>
<?php find_selected_page(); ?>
<div id='main'>
	<div id='navigation'>
       <?php echo navigation($selected_subject_details, $selected_page_details); ?>
    </div>
	<div id='page'>
	<?php	echo message();	?>
	 <?php
		
$errors = errors ();
		?>
	 
	<?php echo form_errors($errors); ?>
		
		<h2>Create a page in <?php echo $selected_subject_details["menu_name"]; ?></h2>
		<form action="create_page.php" method="post">
		<input type="hidden" name="subject_id" value="<?php echo $selected_subject_details["id"]; ?>"/>
			<p>
				Menu name: <input type="text" name="menu_name" value="" />
			</p>
			<p>
				Position: <select name="position">
					<?php
					
					$page_set = find_pages_by_subject_id ( $selected_subject_details ["id"] );
					$page_count = mysqli_num_rows ( $page_set );
					for($count = 1; $count <= ($page_count + 1); $count ++) {
						echo "<option value=" . $count . ">" . $count . "</option>";
					}
					?>
				</select>
			</p>
			<p>
				Visible: <input type="radio" value="0" name="visible" /> No &nbsp; <input
					type="radio" value="1" name="visible" /> Yes
			</p>
			<p>
			Content:<br><br>
				<textarea rows="5" cols="50" name="content"></textarea>
			</p>
			<br>
			<input class="save" type="submit" name="submit"
				value="Create Page" />
				&nbsp; <a href="manage_content.php">Cancel</a>
		</form>
		
	</div>
</div>

<?php
include ("../includes/layouts/footer.php");
?>