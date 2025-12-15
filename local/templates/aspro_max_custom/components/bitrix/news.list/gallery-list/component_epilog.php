<?use Aspro\Max\Functions\Extensions;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arScripts = ['swiper', 'fancybox', 'cross'];
Extensions::init($arScripts);

if ($arParams['USE_FILTER'] != 'N') {
    Extensions::init('section_filter');
}?>
