<?php
namespace Amikomnew;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
class CookieCRM {
    public static function setCookieCRM() {
        if (!empty($_GET['utm_source']) && !empty($_GET['utm_medium']) && !empty($_GET['utm_campaign'])) {
            $dataDirect = [
                'UTM_SOURCE' => empty($_GET['utm_source']) ? $_REQUEST['utm_source'] : $_GET['utm_source'],
                'UTM_MEDIUM' => empty($_GET['utm_medium']) ? $_REQUEST['utm_medium'] : $_GET['utm_medium'],
                'UTM_CAMPAIGN' => empty($_GET['utm_campaign']) ? $_REQUEST['utm_campaign'] : $_GET['utm_campaign']
            ];
            self::setCookieAllInfo($dataDirect);
        }
    }
    public static function setCookieAllInfo ($itemsUTM) {
        foreach ($itemsUTM as $key => $item) {
            if (!empty($item)) { // Проверяем значение $item
                setcookie($key, $item, time() + 60*60*24*2, '/', $_SERVER['SERVER_NAME']); // Порядок аргументов исправлен
            }
        }
    }

    public static function writeUTMField ($cookieType, $fieldCookie,$SID){
        
        $valueHidden = $_COOKIE[strval($cookieType)];
        if (empty($valueHidden)) {
            $valueHidden = $_GET[strtolower($cookieType)];
        } 
        $arStructures = $fieldCookie;
        foreach($arStructures as $itemStruct):
            echo '<input type="hidden" id="'.$itemStruct['ID'].'" data-sid="'.$SID.'" name="form_hidden_'.$itemStruct['ID'].'" value="'.$valueHidden.'"/>';		
        endforeach;
    }
}
?>