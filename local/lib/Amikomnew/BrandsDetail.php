<?
namespace Amikomnew;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

use CFile;
use CIBlock;
use CIBlockSection;
use CIBlockElement;
use DateTime;
setlocale(LC_TIME, 'ru_RU.UTF-8');
date_default_timezone_set('Europe/Moscow');
class BrandsDetail{
    
    public static function GetSectionsBrands() {
        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'NAME',
            'CODE',
            'DESCRIPTION'
        ];
        $filter = [
            'IBLOCK_ID' => 10,
        ];
        $dataSections = [];
        $sectionsResult = CIBlockSection::GetList(['SORT'=>'ASC'], $filter,false,$arSelect);
        while ($object = $sectionsResult->GetNextElement()) {
            $valuesFields = $object->GetFields();
            $vrData =[
                'ID'=>$valuesFields['ID'], 
                'IBLOCK_ID'=>$valuesFields['IBLOCK_ID'], 
                'NAME'=>$valuesFields['NAME'],
                'CODE'=>'/docs/'.$valuesFields['CODE'].'/',
                'DESCRIPTION'=>$valuesFields['DESCRIPTION'],
               
            ];
            $dataSections[] = $vrData;
        }  
        return $dataSections;
    }


    public static function getElementsNewsBrands($brandID,$elementsNot) {
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'NAME',
            'CODE',
            'ACTIVE',
            'PREVIEW_PICTURE',
            'PROPERTY_BRAND_NEWS',
            'IBLOCK_SECTION_ID',
            'DATE_ACTIVE_FROM'
        ];
        $arFilter = [
            'IBLOCK_ID'=>23,
            'PROPERTY_BRAND_NEWS'=>intval($brandID),
            '!ID'=>$elementsNot,
        ];
        $elementsNexted = [];

        $months = [
            1 => 'января',
            2 => 'февраля',
            3 => 'марта',
            4 => 'апреля',
            5 => 'мая',
            6 => 'июня',
            7 => 'июля',
            8 => 'августа',
            9 => 'сентября',
            10 => 'октября',
            11 => 'ноября',
            12 => 'декабря'
        ];

        $elemsRem = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,['nTopCount'=>8],$arSelect);
        while ($obj = $elemsRem->GetNextElement())
        {
            $valuesFields = $obj->GetFields();
            $date =  DateTime::createFromFormat('d.m.Y', $valuesFields['DATE_ACTIVE_FROM']);
            $dtForm = $date->format('j').' '.$months[(int)$date->format('n')].' '.$date->format('Y');
            $elementsNexted[] = [
                'ID'=>$valuesFields['ID'],
                'NAME'=>$valuesFields['NAME'],
                'CODE'=>'/news/'.$valuesFields['CODE'].'/',
                'PREVIEW_PICTURE'=>CFile::GetByID($valuesFields['PREVIEW_PICTURE'])->Fetch()['SRC'],
                'IBLOCK_SECTION_ID'=>CIBlockSection::GetByID($valuesFields['IBLOCK_SECTION_ID'])->Fetch()['NAME'],
                'DATE_ACTIVE_FROM'=> $dtForm,
            ];
        }
        return $elementsNexted;
    }

    public static function getElementsNewsBrandsCount($brandID) {
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'PROPERTY_BRAND_NEWS'
        ];
        $arFilter = [
            'IBLOCK_ID'=>23,
            'PROPERTY_BRAND_NEWS'=>intval($brandID),
        ];
        $elemsRemCount = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,$arSelect)->SelectedRowsCount();
        return strval($elemsRemCount);
    }
    
}
?>