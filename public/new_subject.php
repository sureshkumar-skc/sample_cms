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
	 <?php $errors = errors(); 
	 ?>
	 
	<?php echo form_errors($errors); ?>
		
		<h2>Create Subject</h2>
		<form action="create_subject.php" method="post">
			<p>
				Menu name: <input type="text" name="menu_name" value="" />
			</p>
			<p>
				Position: <select name="position">
					<?php
					
					$subject_set = find_all_subjects ();
					$subject_count = mysqli_num_rows ( $subject_set );
					for($count = 1; $count <= ($subject_count + 1); $count ++) {
						echo "<option value=" . $count . ">" . $count . "</option>";
					}
					?>
				</select>
			</p>
			<p>
				Visible: <input type="radio" value="0" name="visible" /> No &nbsp; 
						 <input type="radio" value="1" name="visible" /> Yes
			</p>
			<input class="save" type="submit" name="submit"
				value="Create Subject" />
		</form>
		<br /> <a href="manage_content.php">Cancel</a>
	</div>
</div>

<?php
include ("../includes/layouts/footer.php");
?>