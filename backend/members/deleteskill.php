<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/_config/bootstrap.php");
	$skid = $_POST['skillid'];
	$userid = $_SESSION['user_id'];

	members_delete_skill($userid, $skid);
	echo "Skill Deleted";
?>