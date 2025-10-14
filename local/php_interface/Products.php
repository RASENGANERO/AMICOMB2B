<?
use Bitrix\Catalog\Model\Price;
class Products {
    public static function priceSortElement($arFields) {
        if (intval($arFields['IBLOCK_ID']) === 29) {
            $priceData = Price::getList([
                'filter' => ['PRODUCT_ID' => $arFields['ID']],
                'select' => ['PRICE'],
                'limit' => 1,
            ])->fetch();
            if (intval($priceData['PRICE']) < 10) {
                $arFields['SORT'] = 1000;
            }
            else {
                $arFields['SORT'] = 100;
            }
        }
    }

    public static function hidePrice ($ID,&$arFields) {
        $fields = $arFields->getParameter('fields');
        $val1 = '';
        $db_props = CIBlockElement::GetProperty(29, $arFields['PRODUCT_ID'], ['sort' => 'asc'], ['CODE' => 'CML2_TRAITS']);
        while ($ob = $db_props->GetNext()) {
            if(strval($ob['DESCRIPTION']) === 'НеПоказыватьЦенуНаСайте'){
                $val1 = $ob['VALUE'];
                break; 
            }
        }
        if (!empty($val1)) {
            if (strval($val1) === 'true') {
                $arFields['PRICE'] = 0.00;
            }
        }
        
    }


    public static function hidePriceElement($arFields) {
        $fields = $arFields->getParameter('fields');
        global $DB;
        $val1 = '';
        $db_props = CIBlockElement::GetProperty(29, $fields['PRODUCT_ID'], ['sort' => 'asc'], ['CODE' => 'CML2_TRAITS']);
        while ($ob = $db_props->GetNext()) {
            if(strval($ob['DESCRIPTION']) === 'НеПоказыватьЦенуНаСайте'){
                $val1 = $ob['VALUE'];
                break; 
            }
        }
        if (!empty($val1)) {
            if (strval($val1) === 'true') {
                $priceData = Price::getList([
                    'filter' => ['PRODUCT_ID' => intval($fields['PRODUCT_ID'])],
                    'select' => ['*'],
                    'limit' => 1,
                ])->fetch();
                $priceData['PRICE'] = 0.00;
                $query = "UPDATE b_catalog_price SET PRICE = " . floatval($priceData['PRICE']) . " WHERE PRODUCT_ID = " . intval($fields['PRODUCT_ID']) . ";";
                $DB->Query($query);
                
            }
        }
    }
	public static function setSortByPrice($arFields) {
		$fields = $arFields->getParameter('fields');
		global $DB;
		$priceData = Price::getList([
			'filter' => ['PRODUCT_ID' => intval($fields['PRODUCT_ID'])],
			'select' => ['PRICE'],
			'limit' => 1,
		])->fetch();
		$sort = 0;
		if (intval($priceData['PRICE']) < 10) {
			$sort = 1000;
		}
		else {
			$sort = 100;
		}
		$query = "UPDATE b_iblock_element SET SORT = " . intval($sort) . " WHERE ID = " . intval($fields['PRODUCT_ID']) . ";";
		$DB->Query($query);
	}
	public static function addUtmHandler(\Bitrix\Main\Event $event) {
        $order = $event->getParameter("ENTITY");
        $commentbitrix24 = "";// Получаем объект заказа
        $dataDirectOrder = [
            'UTM_SOURCE' => empty($_COOKIE['UTM_SOURCE']) ? $_GET['utm_source'] : $_COOKIE['UTM_SOURCE'],
            'UTM_MEDIUM' => empty($_COOKIE['UTM_MEDIUM']) ? $_GET['utm_medium'] : $_COOKIE['UTM_MEDIUM'],
            'UTM_CAMPAIGN' => empty($_COOKIE['UTM_CAMPAIGN']) ? $_GET['utm_campaign'] : $_COOKIE['UTM_CAMPAIGN']
        ];
        $commentbitrix24 .= 'UTM_SOURCE: '.$dataDirectOrder['UTM_SOURCE'].'\n';
        $commentbitrix24 .= 'UTM_MEDIUM: '.$dataDirectOrder['UTM_MEDIUM'].'\n';
        $commentbitrix24 .= 'UTM_CAMPAIGN: '.$dataDirectOrder['UTM_CAMPAIGN']; 
        // Устанавливаем сформированный комментарий менеджера для заказа
        $order->setField("COMMENTS", $commentbitrix24);
    }
}

?>