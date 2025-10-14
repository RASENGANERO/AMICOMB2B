<?
$_SERVER['DOCUMENT_ROOT'] = "D:/OPENSERVER/domains/AMIKOMNEW";
$prolog = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php";
include($prolog);
CModule::IncludeModule('iblock');
CModule::IncludeModule('form');



$arFields = [
    'ID',
    'IBLOCK_ID',
    'NAME',
    'CODE',
    'ACTIVE',
    'DETAIL_TEXT'
];
$arFilter = [
    'IBLOCK_ID' => 23,
    'ACTIVE' => 'Y',
    '!DETAIL_TEXT'=>false
];
$arElements = [];

$resultDT = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,$arFields);

while($res = $resultDT->Fetch()) {
    preg_match_all('/<img[^>]+src="([^">]+)"/i', $res['DETAIL_TEXT'], $matches);
        
    // Если найдены ссылки, добавляем их в массив элементов
    if (!empty($matches[1])) {
        $arElements[] = [
            'ID' => $res['ID'],
            'NAME' => $res['NAME'],
            'LINKS' => $matches[1], // Массив ссылок
          //  'DETAIL_TEXT' => $res['DETAIL_TEXT']
        ];
    }
    
}
print_r($arElements);
for($i=0;$i<count($arElements);$i++){
    $dtFile = $arElements[$i]['LINKS'];
    for ($j = 0; $j < count($dtFile); $j++) {
        if(str_starts_with($dtFile[$j],'/upload') === true){
            if (!file_exists('D:/OPENSERVER/domains/AMIKOMNEW'.$dtFile[$j])) {
                // Заменяем обратные косые черты на прямые и добавляем путь к DIR
                $fileServer[] = $arElements[$i]['ID'].'-->'.$dtFile[$j].PHP_EOL;
            }
        }
        
    }
}
print_r($fileServer);

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