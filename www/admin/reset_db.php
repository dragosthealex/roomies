<?php
require_once '../../inc/init.php';

$stmt = $con->prepare("DELETE FROM rusers");
$stmt->execute();
$stmt = $con->prepare("DELETE FROM ruser_qa");
$stmt->execute();
$stmt = $con->prepare("DELETE FROM rusersettings");
$stmt->execute();
$stmt = $con->prepare("DELETE FROM rgroups");
$stmt->execute();
$stmt = $con->prepare("DELETE FROM rdetails");
$stmt->execute();
$stmt = $con->prepare("DELETE FROM rconexions");
$stmt->execute();
$stmt = $con->prepare("DELETE FROM raccommodations");
$stmt->execute();
$stmt = $con->prepare("DELETE FROM rowners");
$stmt->execute();

?>