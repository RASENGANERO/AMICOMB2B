<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?php 
$frame = $this->createFrame()->begin('');
?>
	<div class="content_wrapper_block"><div class="maxwidth-theme wide"></div></div>
<?php 
$frame->end();
?>

<?php 
TSolution\Extensions::init('top_tabs');
