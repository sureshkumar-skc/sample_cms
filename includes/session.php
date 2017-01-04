<?php
session_start ();
function message() {
	if (isset ( $_SESSION ["message"] )) {
		$output = "<div class=\"message\">";
		$output .= htmlentities($_SESSION ["message"]);
		$output .= "</div>";
		$_SESSION ["message"] = null;
		return $output;
	}
}

function errors() {
	if (isset ( $_SESSION ["form_errors"] )) {
		$error = $_SESSION ["form_errors"];
		$_SESSION ["form_errors"] = null;
		return $error;
	}
}
?>