<?
$_SERVER['DOCUMENT_ROOT'] = "D:/OPENSERVER/domains/AMIKOMB2BNEW";
$prolog = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php";
include($prolog);
CModule::IncludeModule('iblock');
CModule::IncludeModule('form');


$arValuesDiscount = [];
// Получаем значения свойства
$propValuesRes = CIBlockElement::GetProperty(33, 310731, 'sort', 'asc', ['CODE' => 'B2B_DISCOUNT']);

while ($res = $propValuesRes->Fetch()) {
    $arValuesDiscount[] = $res['VALUE'];
}

CModule::IncludeModule("sale");


use Bitrix\Sale;
use Bitrix\Main\Context;

// Получаем идентификатор пользователя
$fuserId = Sale\Fuser::getId();

// Получаем текущий сайт
$siteId = Context::getCurrent()->getSite();

// Загружаем корзину для текущего пользователя
$basket = Sale\Basket::loadItemsForFUser($fuserId, $siteId);

// Получаем элементы корзины
$basketItems = $basket->getBasketItems();

// Выводим информацию о товарах в корзине
$allProductPrices = \Bitrix\Catalog\PriceTable::getList([
    'select' => ["*"],
    'filter' => [
        '=PRODUCT_ID' => 185420,
    ],
])->fetchAll();
print_r($allProductPrices);
use AmikomB2B;
$discountsAll = \AmikomB2B\DiscountInfo::getDiscounts('018e027a-edf1-11ea-817d-0cc47a7821bb', 'hiwatch');




?>