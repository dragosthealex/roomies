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
$headers = getallheaders();
if (isset($headers['Roomies']) && $headers['Roomies'] == 'kiwi')
{
    // Output the iframe script
    echo "<!-- Iframe!!! -->";
} // if
else
{
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
