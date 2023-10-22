<?php
include_once("inc/DB.class.php");
$db = new DB();
$table_name="ZT_badges";

$row = $db->getRows($table_name,array());

