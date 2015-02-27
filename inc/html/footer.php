<?php
/*
To do:
1 Output footer content
(2 Close body/html)
For the sake of layout, let the body/html be closed on every page
*/
?>
<!-- Footer -->
<div class="box">
	<ul class="box-padding footer">
		<li class="footer-item">Roomies &copy; 2014</li>
		<li class="footer-item"><a href="#" class="footer-link">About</a></li>
		<li class="footer-item"><a href="#" class="footer-link">Terms</a></li>
		<li class="footer-item"><a href="#" class="footer-link">Privacy</a></li>
		<li class="footer-item"><a href="#" class="footer-link">Cookies</a></li>
	</ul>
</div>

<!--closing main-->
</div>

<!-- Scripts -->
<?php
if (isset($_SERVER['HTTP_ROOMIES']) && $_SERVER['HTTP_ROOMIES'] == 'kiwi')
{
    // Output the iframe script
    echo "<!-- Iframe!!! -->";
} // if
else
{
    if (isset($user))
    {
        $timestamp = date('Y-m-d H:i:s');
        $userId = $user->getIdentifier('id');
        $stmt = $con->prepare("SELECT message_id FROM rmessages ORDER BY message_id DESC LIMIT 1");
        $stmt->bindColumn(1, $lastMessageId);
        $stmt->execute();
        $stmt->fetch();
        echo "<script>roomiesInfo={lastMessageId:$lastMessageId,userId:$userId,webRoot:'$webRoot'};</script>";
    }
    echo "<script>roomiesInfo={webRoot:'$webRoot'}</script>";
    // Output the global scripts
    echo "<script src='$webRoot/media/js/global.js'></script>";
    // If there are other scripts to output, output those too
    if (isset($scripts))
    {
        foreach ($scripts as $script)
        {
            echo "<script src='$webRoot/media/js/$script.js'></script>";
        }
    }
    // Output the script to delete the global methods (prevent users using them)
    echo "<script src='$webRoot/media/js/close.js'></script>";
} // else
?>
</body>
</html>
