<?php
/*
To do:
1 Output doctype..title
2 Output $title
3 Output title..body
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=$title?></title>
  <link rel="stylesheet" type="text/css" href="<?=$webRoot?>/media/css/style.css">
</head>
<body class="body <?=LOGGED_IN?'logged-in':'logged-out'?>">
<script src="<?=$webRoot?>/media/js/facebook_api.js"></script>