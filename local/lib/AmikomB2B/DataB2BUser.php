<?
namespace AmikomB2B;
use CIBlockElement;
use Bitrix\Highloadblock\HighloadBlockTable;
use CUser;
use CCatalogStore;

class DataB2BUser {
    public static function getCompany($userPartnerID) {
        $arFilter = [
            'IBLOCK_ID' => 56,
            'PROPERTY_PARTNER_B2B' => $userPartnerID,
        ];
        $arFieldsSelect = [
            'NAME',
            'PROPERTY_INN',
            'PROPERTY_KPP',
        ];
        $res = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,$arFieldsSelect)->Fetch();
        return $res;
    }
    public static function getAgree($userPartnerID) {
        $arFilter = [
            'IBLOCK_ID' => 57,
            'PROPERTY_PARTNER_B2B' => $userPartnerID,
        ];
        $arFieldsSelect = [
            'NAME',
            'PROPERTY_NUMBER',
            'PROPERTY_DATE_START',
        ];
        $res = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,$arFieldsSelect)->Fetch();
        return $res;
    }
    public static function getManager($userPartnerID) {
        $arFilter = [
            'IBLOCK_ID' => 54,
            'PROPERTY_PARTNER_B2B' => $userPartnerID,
        ];
        $arFieldsSelect = [
            'PROPERTY_MANAGER',
        ];
        $resManagerID = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,$arFieldsSelect)->Fetch()['PROPERTY_MANAGER_VALUE'];
        $arHLBlock = HighloadBlockTable::getById(9)->fetch();
        $obEntity = HighloadBlockTable::compileEntity($arHLBlock);
        $entityClass = $obEntity->getDataClass();
        $res = $entityClass::getList([
            'select' => ['*'],
            'filter' => ['UF_XML_ID' => $resManagerID]
        ])->fetch();
        if (!empty($res)) {
            $res['UF_PHONE_FORMATTED'] = '';
            if (!empty($res['UF_PHONE'])) {
                $res['UF_PHONE_FORMATTED'] = self::formatPhoneNumber($res['UF_PHONE']);
            }
        }
        return $res;
    }
    public static function formatPhoneNumber($phone) {
		$phone = preg_replace('/[^\d]/', '', $phone);
		return '+7 ' . substr($phone, 1, 3) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7, 2) . ' ' . substr($phone, 9, 2);
    }
    public static function getUserForm() {
        global $USER;
        $res = \CUser::GetByID($USER->GetID())->Fetch()['LOGIN'];
        return $res;
    }
        

    public static function getDiscountsBrands($ID_USER) {
        $arDataDiscounts = [];
        $arHLBlock = HighloadBlockTable::getById(10)->fetch();
        $obEntity = HighloadBlockTable::compileEntity($arHLBlock);
        $strEntityDataClass = $obEntity->getDataClass();
        $rsData = $strEntityDataClass::getList([
            'select' => ['UF_DISCOUNT_VALUE','UF_PRICE_GROUP'],
            'filter' => ['UF_PARTNER_ID'=> $ID_USER],
            'count_total' => 1,
        ]);
        $arFilter = [
            'IBLOCK_ID'=>33,
            'ACTIVE'=>'Y'
        ];
        if ($rsData->getCount() > 0) {
            while($obDiscount = $rsData->fetch()) {
                $list = [];
                $list['DISCOUNT'] = $obDiscount['UF_DISCOUNT_VALUE'];
                $list['GROUP'] = $obDiscount['UF_PRICE_GROUP'];
                $arFilter['PROPERTY_B2B_DISCOUNT'] = $obDiscount['UF_PRICE_GROUP'];
                $res = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,['ID'])->Fetch()['ID'];
                if (!empty($res)) {
                    $list['ID'] = $res;
                    $arDataDiscounts[] = $list;
                }
            }
        }
        return $arDataDiscounts;
    }
    public static function generateDiscBrands($dataDiscounts) {
        $maxDiscounts = [];
        foreach ($dataDiscounts as $item) {
            $id = $item['ID'];
            $discount = $item['DISCOUNT'];
            if (!isset($maxDiscounts[$id]) || $discount > $maxDiscounts[$id]) {
                $maxDiscounts[$id] = $discount;
            }
        }
        $uniqueIds = array_keys($maxDiscounts);
        sort($uniqueIds);
        return ['IDS'=>$uniqueIds, 'DISCOUNTS'=>$maxDiscounts];
    }
    public static function getCountElement($IdElement) {
        $dbResult =  CCatalogStore::GetList(['PRODUCT_ID'=>'ASC','ID' => 'ASC'],['ACTIVE' => 'Y','PRODUCT_ID'=>[$IdElement],'!PRODUCT_AMOUNT'=>''],false,false,['PRODUCT_AMOUNT']);
		$k = 0;
		while($ob = $dbResult->Fetch()){
			$k += intval($ob['PRODUCT_AMOUNT']);
		}
        return $k;
    }
    public static function getNameSeller($userID) {
        $arFilter = [
            'PROPERTY_PARTNER_B2B_VALUE' => $userID,
            'IBLOCK_ID' => 56
        ];
        $res = CIBlockElement::GetList(['SORT'=>'ASC'],$arFilter,false,false,['NAME'])->Fetch()['NAME'];
        return $res;
    }
    public static function getDeliveryOrder($dataDelivery,$orderID){
        $arDelivery['NAME'] = $dataDelivery['DELIVERY_NAME'];
        if (intval($dataDelivery['DELIVERY_ID']) === 3) {
            $arDelivery['ADDRESS'] = 'г. Москва, Переведеновский переулок, 17к2';
        }
        else {
            $orderCs=\Bitrix\Sale\Order::load($orderID);
            $propertyCollection = $orderCs->getPropertyCollection();
            foreach ($propertyCollection as $propertyItem) {
                if($propertyItem->getField("CODE") == "ADDRESS"){
                    $arDelivery['ADDRESS'] = $propertyItem->getValue();
                }
            }
        }
        return $arDelivery;
    }
    public static function getDeliveryPriceBasket($data){
        $data = array_values($data);
        $cntPrice = floatval(0);
        for ($i = 0; $i < count($data); $i++) {
            $cntPrice+=$data[$i]['PRICE'];
        }
        $cntPrice = \AmikomB2B\DiscountPrices::getPrintValue($cntPrice);
        return $cntPrice;
    }
}

?>