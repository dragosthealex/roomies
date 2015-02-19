<?php
/*
To do:
1 Output 404 not found message
*/
header("HTTP/1.0 404 Not Found");
if (isset($_SERVER['HTTP_ROOMIES']) && ($_SERVER['HTTP_ROOMIES'] == 'cactus' || $_SERVER['HTTP_ROOMIES'] == 'kiwi'))
{
    exit('not found!!1!');
}
$title = 'Page not found';
include __ROOT__.'/inc/html/head.php';
include __ROOT__."/inc/html/header.$ioStatus.php";
?>
<div class="box">
    <div class="box-padding">
        <h2 class="h2" id="Page_not_found">Page not found</h2>
        <p class="text">The requested URL <?=$_SERVER['REQUEST_URI']?> was not found on our servers.</p>
    </div>
</div>
<?php
include __ROOT__.'/inc/html/footer.php';
exit();
?>