<?php
/**
* Class Request
*
* Represents the requests of kinds
*/
require_once __ROOT__.'/inc/classes/Base.php';
class Request
{
  public static $template = array(
    '<li class="drop-item friend-request" id="drop-item-fr-',
    // $otherUserId
    '" data-fr-id="',
    // $otherUserId
    '"><div class="drop-item-box"><a class="drop-item-pic" href="/profile/?u=',
    // $otherUsername
    '" style="background-image: url(/media/img/anonymous.jpg)"></a><h3 class="drop-item-header"><div class="drop-item-header-right"><a data-ajax-url="../php/friends.process.php?a=3&amp;id=',
    // $otherUserId
    '" data-ajax-text="Accepting..." data-ajax-callback-1="deleteById drop-item-fr-',
    // $otherUserId
    '" data-ajax-callback-2="updateNofifCount" class="link-button button2">Accept</a> <a data-ajax-url="../php/friends.process.php?a=0&amp;id=',
    // $otherUserId
    '" data-ajax-text="Ignoring..." data-ajax-callback-1="deleteById drop-item-fr-',
    // $otherUserId
    '" data-ajax-callback-2="updateNofifCount" class="link-button button2">Ignore</a></div><a href="/profile/',
    // $otherUsername
    '" class="link">',
    // $otherUsername
    '</a></h3><p class="drop-item-footer"></p><p class="drop-item-text" style="color:rgb(',
    // (160-160*$percentage/100)
    ',',
    // (160*$percentage/100)
    ',0)">',
    // $percentage
    '%</p></div></li>',
  );
}
?>