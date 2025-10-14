<?
namespace Amikomnew;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

use CIBlockSection;
class SubsectionsTree {
    public static function getSections($parentId = 0) {
            $result = [];
    
            // Получаем разделы инфоблока
            $sections = CIBlockSection::GetList(
                ["LEFT_MARGIN" => "ASC"], // Сортировка по полю SORT
                ["IBLOCK_ID" => 29, "SECTION_ID" => $parentId], // Указываем инфоблок и родительский ID
                true,
                ['ID','NAME','CODE','SECTION_ID'] // Учитываем только активные разделы
            );
    
            while ($section = $sections->Fetch()) {
                // Создаем элемент массива для текущего раздела
                $url =  CIBlockSection::GetNavChain(
                    29,$section['ID'],array(),true
                );
                $arrData = [];
                foreach ($url as $arSectionPath){
                    $arrData[] = $arSectionPath['CODE'];
                }
                $isRoots = count($arrData);
                $arrData = '/catalog/'.implode('/',$arrData).'/';
                $arrData = str_replace('\\', '',  $arrData);

                $sectionArray = [
                    'ID' => $section['ID'],
                    'NAME' => $section['NAME'],
                    'URL'=>$arrData,
                    'ROOT'=>$isRoots === 1 ? 0 : $section['ID'],
                    'CHILDREN' => SubsectionsTree::getSections( $section['ID']), // Рекурсивно получаем дочерние разделы
                ];
    
                // Добавляем текущий раздел в результат
                $result[] = $sectionArray;
            }
    
            return $result;
        }
    }
?>