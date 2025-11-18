<?
$_SERVER['DOCUMENT_ROOT'] = "D:/OPENSERVER/domains/AMIKOMNEW";
$prolog = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php";
include($prolog);
CModule::IncludeModule('iblock');
CModule::IncludeModule('form');



// пространства имен highloadblock
use Bitrix\Highloadblock\HighloadBlockTable;
// подключаем модуль highloadblock
\Bitrix\Main\Loader::includeModule("highloadblock");
// делаем выборку хайлоуд блока с ID 4
$arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById(7)->fetch();
// инициализируем класс сущности хайлоуд блока с ID 4
$obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
// обращаемся к DataManager
$strEntityDataClass = $obEntity->getDataClass();
// стандартный запрос getList
$rsData = $strEntityDataClass::getList(array(
    // необходимые для выборки поля
    'select' => array('ID', 'UF_NAME', 'UF_XML_ID')
));
// формируем массив данных
while ($arItem = $rsData->Fetch()) {
    $arItems[] = [
        'ID' => $arItem['ID'],
        'UF_NAME' => $arItem['UF_NAME'],
        'UF_XML_ID' => $arItem['UF_XML_ID'],
    ];
}


for($i=0;$i<count($arItems);$i++) {
    $arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById(10)->fetch();
    // инициализируем класс сущности хайлоуд блока с ID 4
    $obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
    // обращаемся к DataManager
    $strEntityDataClass = $obEntity->getDataClass();
    $rsData = $strEntityDataClass::getList(array(
        // необходимые для выборки поля
        'select' => array('ID', 'UF_XML_ID', 'UF_PRICE_GROUP'),
        'filter' => ['UF_PRICE_GROUP'=> $arItems[$i]['UF_XML_ID']],
        'count_total' => 1,
    ));
    if (intval($rsData->getCount()) > 0) {
        $arItems[$i]['DEL'] = 'N';
    }
    else {
        $arItems[$i]['DEL'] = 'Y';
    }
}

print_r($arItems);




// Открываем файл для записи
$fp = fopen('output.csv', 'w');

// Записываем заголовки
fputcsv($fp, array_keys($arItems[0]));

// Записываем данные
foreach ($arItems as $row) {
    fputcsv($fp, $row);
}

// Закрываем файл
fclose($fp);

/*function getDirContents($dir, $results = array()) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = str_replace('\\','/',$path);
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
           // $results[] = $path;
        }
    }
    return $results;
}
function removePath($arData) {
    $sp = [];
    foreach ($arData as $filePath) {
        // Используем basename для получения имени файла
        $sp[] = basename($filePath);
    }
    return $sp;
}

$allFiles = getDirContents(__DIR__.'/upload/news/');
print_r($allFiles);



$fileServer = [];
for ($i = 0; $i < count($arElements); $i++) {
    $dtFile = $arElements[$i]['LINKS'];
    for ($j = 0; $j < count($dtFile); $j++) {
        // Проверяем, содержится ли 'bitrix' в строке
        if (strpos($dtFile[$j], 'bitrix') === false) {
            // Заменяем обратные косые черты на прямые и добавляем путь к DIR
            $fileServer[] = str_replace('\\', '/', __DIR__ . $dtFile[$j]);
        }
    }
}
/*
$k = 1;
for ($i = 0; $i < count($arElements); $i++) {
    // Получаем элемент инфоблока
    $elementId = $arElements[$i]['ID']; // Замените на ID вашего элемента
    $element = CIBlockElement::GetByID($elementId);
    
    if ($arElement = $element->GetNext()) {
        // Получаем текущее значение DETAIL_TEXT
        $detailText = $arElement['DETAIL_TEXT'];

        // Используем регулярное выражение для поиска всех тегов <img>
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $detailText, $matches);

        // Проверяем, найдены ли теги
        if (!empty($matches[0])) {
            // Изменяем атрибут src у каждого найденного тега
            foreach ($matches[0] as $imgTag) {
                // Извлекаем текущий src
                preg_match('/src=["\']([^"\']+)["\']/i', $imgTag, $srcMatch);
                if (!empty($srcMatch[1])) {
                    if (str_ends_with($srcMatch[1],'.jpg') || str_ends_with($srcMatch[1],'.png') || str_ends_with($srcMatch[1],'.gif')) {
                        $oldSrc = $srcMatch[1];
                        // Новый src (например, добавляем префикс или изменяем путь)
                        $newSrc = '/upload/news/' .$k.'.'. end(explode('.',basename($oldSrc))); // Измените по вашему усмотрению
                        
                        copy('D:/OPENSERVER/domains/AMIKOMOLD'.$oldSrc, 'D:/OPENSERVER/domains/AMIKOMNEW'.$newSrc);
                        //echo $newSrc.PHP_EOL;
                        // Заменяем старый src на новый
                        $newImgTag = str_replace($oldSrc, $newSrc, $imgTag);
                        // Заменяем в DETAIL_TEXT
                        $detailText = str_replace($imgTag, $newImgTag, $detailText);
                        $k+=1;
                        $arDet = [
                            'MODIFIED_BY' => 1,
                            'DETAIL_TEXT' => $detailText
                        ];
                        $elementUpdate = new CIBlockElement;
                        $elementUpdate->Update($elementId, $arDet);
                    }
                    
                }
            }

         
        } else {
            echo "Теги <img> не найдены.";
        }
    } else {
        echo "Элемент не найден.";
    }
}*/

/*
for($i = 0; $i < count($arElements); $i++){
    // Получаем элемент инфоблока
    $elementId = 1; // Замените на ID вашего элемента
    $element = CIBlockElement::GetByID($elementId);
    
    if ($arElement = $element->GetNext()) {
        // Получаем текущее значение DETAIL_TEXT
        $detailText = $arElement['DETAIL_TEXT'];

        // Задаем новое значение src для изображений
        $newSrc = "https://example.com/new-image.jpg"; // Замените на ваш новый URL

        // Используем регулярное выражение для замены атрибута src
        $updatedDetailText = preg_replace('/(<img[^>]+src=["\'])([^"\']+)(["\'][^>]*>)/i', '$1' . $newSrc . '$3', $detailText);

        // Обновляем элемент с новым DETAIL_TEXT
        $el = new CIBlockElement;
        $el->Update($elementId, array('DETAIL_TEXT' => $updatedDetailText));

        echo "Атрибут src успешно обновлен!";
    } else {
        echo "Элемент не найден.";
    }
}*/


/*
print_r($fileServer);
for ($i = 0; $i<count($allFiles); $i++) {
    if (!in_array($allFiles[$i], $fileServer)) {
        unlink($allFiles[$i]);
    }
}*/

//return $filePath;

/*$arFieldsSel = [
    'ID',
    'IBLOCK_ID',
    'CODE',
    'NAME',
    'ACTIVE',
    'PREVIEW_PICTURE'
];

$arFilter = [
    'IBLOCK_ID'=>29,
    'PREVIEW_PICTURE'=>false,
];

$elemList = [];

$elements = CIBlockElement::GetList(['SORT'=>'ASC'], $arFilter,false,false,$arFieldsSel);
while($resElem = $elements->Fetch()){
    $elemList[] = [
        'ID' => $resElem['ID'],
        'IBLOCK_ID' => $resElem['IBLOCK_ID'],
        'CODE' => $resElem['CODE'],
        'NAME' => $resElem['NAME'],
        'ACTIVE'=>$resElem['ACTIVE'],
        'PREVIEW_PICTURE'=>$resElem['PREVIEW_PICTURE'],
    ];
}

file_put_contents('preview_not.txt',serialize($elemList),FILE_APPEND);
print_r($elemList);
*/





?>