<?use Aspro\Max\Functions\Extensions;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if ($arParams['USE_FILTER'] != 'N') {
    Extensions::init('section_filter');
}
?>
