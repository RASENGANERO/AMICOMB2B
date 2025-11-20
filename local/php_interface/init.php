<?
require_once('CheckboxCustom.php');
AddEventHandler('iblock', 'OnIBlockPropertyBuildList', ['CIBlockPropertyCheckbox', 'GetUserTypeDescription']);

use Bitrix\Main;
require_once('Brands.php');
require_once('ShowCustom.php');
require_once('Logger.php');
require_once('Products.php');

# менеджер событий
$eventManager = \Bitrix\Main\EventManager::getInstance();


$eventManager->addEventHandler(
    'main',
    'OnAdminContextMenuShow',
    ['ShowCustom','addBtnShow'],
);

/*
* События, отвечающие за логирование товаров в 1С
*/

$eventManager->addEventHandler("iblock",'OnAfterIBlockElementAdd',['Logger', 'addElement']);
$eventManager->addEventHandler("iblock",'OnAfterIBlockElementUpdate',['Logger', 'updateElement']);
$eventManager->addEventHandler('catalog','\Bitrix\Catalog\Price::OnAfterUpdate',['Logger','updateElementNewPrice']);


/*
* События, отвечающие за установку полей в товарах
*/
$eventManager->addEventHandler(
    'iblock',
    'OnBeforeIBlockElementUpdate',
    ['Products', 'priceSortElement'],
);
$eventManager->addEventHandler(
    'catalog',
    '\Bitrix\Catalog\Price::OnAfterUpdate',
    ['Products', 'hidePriceElement'],
);

$eventManager->addEventHandler(
    'catalog',
    '\Bitrix\Catalog\Price::OnAfterUpdate',
    ['Products', 'setSortByPrice'],
);

/*
* Добавление UTM меток при оформлении заказа
*/
$eventManager->addEventHandler('sale','OnSaleOrderBeforeSaved',['Products','addUtmHandler']);


/*
* События, отвечающие за обновления брендов при импортировании товаров из 1С
*/

# обработчик после добавления товара
$eventManager->addEventHandler(
    'iblock',
    'OnAfterIBlockElementAdd',
    ['Brands', 'brandsAdd'],
);
# обработчик после обновления товара
$eventManager->addEventHandler(
    'iblock',
    'OnAfterIBlockElementUpdate',
    ['Brands', 'brandsUpdate'],
);
spl_autoload_register(function ($class) {
    $prefix = 'Amikomnew\\'; // Используйте двойной обратный слэш
    $base_dir = __DIR__ . '/../lib/Amikomnew/';

    // Проверяем, начинается ли класс с префикса
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Получаем относительное имя класса
    $relative_class = substr($class, strlen($prefix));

    // Формируем путь к файлу
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php'; // Здесь тоже используйте двойной обратный слэш

    // Подключаем файл, если он существует
    if (file_exists($file)) {
        require $file;
    }
});
spl_autoload_register(function ($class) {
    $prefix = 'AmikomnewBrands\\'; // Используйте двойной обратный слэш
    $base_dir = __DIR__ . '/../lib/AmikomnewBrands/';

    // Проверяем, начинается ли класс с префикса
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Получаем относительное имя класса
    $relative_class = substr($class, strlen($prefix));

    // Формируем путь к файлу
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php'; // Здесь тоже используйте двойной обратный слэш

    // Подключаем файл, если он существует
    if (file_exists($file)) {
        require $file;
    }
});
spl_autoload_register(function ($class) {
    $prefix = 'AmikomnewB2B\\'; // Используйте двойной обратный слэш
    $base_dir = __DIR__ . '/../lib/AmikomnewB2B/';

    // Проверяем, начинается ли класс с префикса
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Получаем относительное имя класса
    $relative_class = substr($class, strlen($prefix));

    // Формируем путь к файлу
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php'; // Здесь тоже используйте двойной обратный слэш

    // Подключаем файл, если он существует
    if (file_exists($file)) {
        require $file;
    }
});
?>