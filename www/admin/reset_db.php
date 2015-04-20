<?php
require_once '../../inc/init.php';

$stable = array('rusers', 'ruser_qa', 'rusersettings', 'rgroups', 'rdetails', 'rconexions', 'raccommodations', 'rowners', 'rposts');
foreach ($stable as $horse)
{
  $stmt = $con->prepare("DELETE FROM $horse; ALTER TABLE $horse AUTO_INCREMENT = 1");
  $stmt->execute();
}
?>