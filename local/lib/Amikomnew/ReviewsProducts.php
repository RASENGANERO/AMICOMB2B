<?
namespace Amikomnew;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

use CIBlockElement;
class ReviewsProducts {
    public static function addReview($dataRevs) {
        $idElem = ReviewsProducts::getIdProduct($dataRevs['PRODUCT_CODE']);
        $ratingFinal = ReviewsProducts::getRatingsProduct($idElem, $dataRevs['RAITING']);

        $propsReviews = [
            'RATING' => intval($dataRevs['RAITING']),
            'PRODUCT' => $idElem,
            'EMAIL' => $dataRevs['EMAIL'],
        ];
        $arAddReviews = [
            'MODIFIED_BY' => intval($dataRevs['USERID']),
            'CREATED_BY' => intval($dataRevs['USERID']),
            'SORT' => 500,
	        'IBLOCK_ID' => 22,
	        'PROPERTY_VALUES' => $propsReviews,  
	        'NAME' => $dataRevs['USERNAME'],  
            'ACTIVE' => 'Y',
            'PREVIEW_TEXT' => $dataRevs['TEXT_REVIEWS'],
        ];
        $elementRaiting = new CIBlockElement;
        if($elementRaiting->Add($arAddReviews)) {
            CIBlockElement::SetPropertyValueCode($idElem,'EXTENDED_REVIEWS_RAITING',$ratingFinal);
            return 'SUCCESS';
        } else {
            return 'Error: '.$elementRaiting->LAST_ERROR;
        }
    }
    public static function getIdProduct($code):int {
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'CODE'
        ];
        $arFilter = [
            'IBLOCK_ID' => 29,
            'CODE' => $code,
        ];
        $rsID = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false, $arSelect);
        $rsID = intval($rsID->Fetch()['ID']);
        return $rsID;
    }
    public static function getRatingsProduct($IdElement, $ratingValueForm) {
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'PROPERTY_RATING'
        ];
        $arFilter = [
            'IBLOCK_ID' => 22,
            'PROPERTY_PRODUCT' => $IdElement,
        ];
        $ratingValues = [];
        $resultReviews = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false, $arSelect);
        if ($resultReviews->FieldsCount() === 0) {
            $ratingValues[] = intval($ratingValueForm);
        }
        else {
            while ($reviews = $resultReviews->GetNextElement()) {
                $valuesFields = $reviews->GetFields();
                $ratingValues[] = intval($valuesFields['PROPERTY_RATING_VALUE']);
            }
            $ratingValues[] = intval($ratingValueForm);
        }
        $ratingTotals = ReviewsProducts::getTotalRaiting($ratingValues);
        return $ratingTotals;
    }
    public static function getTotalRaiting($ratingElements) {//Функция генерации общего рейтинга
        $average = floatval(array_sum($ratingElements)/count($ratingElements));
        $average = round($average, 0);
        return intval($average);
    }
}
?>