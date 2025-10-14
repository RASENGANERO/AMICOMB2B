<?

$_SERVER['DOCUMENT_ROOT'] = "D:/OPENSERVER/domains/AMIKOMNEW";
$prolog = $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php";
include($prolog);

CModule::IncludeModule('iblock');





function getValuesCSV($fileName){
    $file = fopen($fileName, "r");
    $arras= [];
    while (($row = fgetcsv($file, 0, ";")) !== false) {// Читаем и разбираем строки TSV-файла (разделитель — табуляция)
        $arras[$row[0]] = $row[2];
    }
    fclose($file);
    return $arras; 
}
$allNews = getValuesCSV("news.csv");
//print_r($allSections);
function getData($allNews){
    $dtBlock = [];
    $arras = unserialize(file_get_contents('D:/OPENSERVER/domains/AMIKOMOLD/sectionNews5.txt'));
    for ($i = 0; $i<count($arras); $i++) {
        $valData = explode('!!!@@@!!!',$arras[$i]);
       
        $datas = [
            'ID' => $allNews[$valData[0]],
            'SECTION_ID' => $valData[1],
        ];
        $dtBlock[] = $datas;
    }
    return $dtBlock;
}
$dtBlock = getData($allNews);

for($i=0;$i<count($dtBlock);$i++) {
    if(!empty($dtBlock[$i]['ID'])) {
        CIBlockElement::SetElementSection($dtBlock[$i]['ID'],[$dtBlock[$i]['SECTION_ID']]);
    }
    
}
print_r($dtBlock);
?>