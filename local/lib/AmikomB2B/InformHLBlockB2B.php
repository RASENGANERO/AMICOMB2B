<?
namespace AmikomB2B;
require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
error_reporting(E_ERROR | E_PARSE);

use Bitrix\Highloadblock\HighloadBlockTable;
class InformHLBlockB2B {
    const HLIBLOCK_MANAGERS = 9;
    const HLIBLOCK_PRICE_GROUP = 7;
    const HLIBLOCK_PRICE_MATRIX = 10;
    public array $partnerManagers;
    public array $priceGroups;
    public array $priceMatrix;
    public string $guidPartner;
    public string $guidXmlGroup;
    function __construct($dataJsonArr)
    {
        $this->partnerManagers = array_values($dataJsonArr['_10_UsersManagers']);
        $this->priceGroups = array_values($dataJsonArr['_12_PriceGroup']);
        $this->priceMatrix = $dataJsonArr;
        $this->guidPartner = $dataJsonArr['_31_Рartner']['_p1']['Guid'];
        $this->guidXmlGroup = $dataJsonArr['_40_TermsAgree']['_p1']['Guid'];
    }
    public function generateDataPriceMatrix($arData):array {
        $termsAgree = array_values($arData['_40_TermsAgree']['_p1']['TermsAgr']);
        $priceGroup = array_values($arData['_12_PriceGroup']);
        $priceGroup = self::genKeysesArr($priceGroup);
        $dataPriceMatrix = [];
        for ($i = 0; $i < count($termsAgree); $i++) {
            if (intval($termsAgree[$i]['Formul']) != 0) {
                $dataPriceMatrix[] = [
                    'UF_XML_ID' => $this->guidXmlGroup,
                    'UF_DISCOUNT_VALUE' => intval($termsAgree[$i]['Formul']) < 0 ? intval($termsAgree[$i]['Formul'])*(-1) : intval($termsAgree[$i]['Formul']),
                    'UF_PRICE_GROUP' => $termsAgree[$i]['PriceGroup'],
                    'UF_PARTNER_ID' => $this->guidPartner,
                    'UF_NAME_GROUP' => $priceGroup[$termsAgree[$i]['PriceGroup']]['Name'],
                ];  
            }
        }
        return $dataPriceMatrix;
    }
   

    public static function genKeysesArr($arData) {
        $arrGrp = [];
        for ($i = 0; $i < count($arData); $i++) {
            $arrGrp[$arData[$i]['Guid']] = $arData[$i];
        } 
        return $arrGrp;
    }


    public static function getEntity($idHLBlock) {
        $arHLBlock = HighloadBlockTable::getById($idHLBlock)->fetch();
        $obEntity = HighloadBlockTable::compileEntity($arHLBlock);
        $strEntityDataClass = $obEntity->getDataClass();
        return $strEntityDataClass;
    }
    public function setManagers($HL_IBLOCK_ID) {
        $entityClass = $this->getEntity($HL_IBLOCK_ID);
        for ($i = 0; $i < count( $this->partnerManagers); $i++) {
            $res = $entityClass::getList([
                'select' => ['*'],
                'filter' => ['UF_XML_ID' => $this->partnerManagers[$i]['Guid']]
            ])->fetch();
            if (!empty($res)) {
                $arFieldUpdate['UF_XML_ID'] = $this->partnerManagers[$i]['Guid'];
                $arFieldUpdate['UF_NAME'] = $this->partnerManagers[$i]['Name'];
                $arFieldUpdate['UF_FIO'] = $this->partnerManagers[$i]['Name'];
                //$arFieldUpdate['UF_PHONE'] = '';
                //$arFieldUpdate['UF_EMAIL'] = '';
                $entityClass::update($res['ID'],$arFieldUpdate);
            }
            else {
                $arFieldHLAdd['UF_XML_ID'] = $this->partnerManagers[$i]['Guid'];
                $arFieldHLAdd['UF_NAME'] = $this->partnerManagers[$i]['Name'];
                $arFieldHLAdd['UF_FIO'] = $this->partnerManagers[$i]['Name'];
                //$arFieldHLAdd['UF_PHONE'] = '';
                //$arFieldHLAdd['UF_EMAIL'] = '';
                $entityClass::add($arFieldHLAdd);
            }
        }
    }


    public function setPriceGroup($HL_IBLOCK_ID) {
        $entityClass = $this->getEntity($HL_IBLOCK_ID);
        for ($i = 0; $i < count( $this->priceGroups); $i++) {
            $res = $entityClass::getList([
                'select' => ['*'],
                'filter' => ['UF_XML_ID' => $this->priceGroups[$i]['Guid']]
            ])->fetch();
            if (!empty($res)) {
                $arFieldUpdate['UF_XML_ID'] = $this->priceGroups[$i]['Guid'];
                $arFieldUpdate['UF_NAME'] = $this->priceGroups[$i]['Name'];
                $entityClass::update($res['ID'],$arFieldUpdate);
                //echo 'UPDATE: '.$res['ID'].' NAME: '.$arFieldUpdate['UF_NAME'].PHP_EOL;
            }
            else {
                $arFieldHLAdd['UF_XML_ID'] = $this->priceGroups[$i]['Guid'];
                $arFieldHLAdd['UF_NAME'] = $this->priceGroups[$i]['Name'];
                $entityClass::add($arFieldHLAdd);
                //echo 'ADD: '.$res['ID'].' NAME: '.$arFieldHLAdd['UF_NAME'].PHP_EOL;
            }
        }
    }
    public function setPriceMatrix($HL_IBLOCK_ID) {
        $entityClass = $this->getEntity($HL_IBLOCK_ID);
        $this->priceMatrix = $this->generateDataPriceMatrix($this->priceMatrix);
        //print_r($this->priceMatrix);
        
        for ($i = 0; $i < count($this->priceMatrix); $i++) {
            $res = $entityClass::getList([
                'select' => ['*'],
                'filter' => [
                    'UF_PRICE_GROUP' => $this->priceMatrix[$i]['UF_PRICE_GROUP'],
                    'UF_PARTNER_ID' => $this->priceMatrix[$i]['UF_PARTNER_ID']
                    ]
            ])->fetch();
            if (!empty($res)) {
                $arFieldUpdate = $this->priceMatrix[$i];
                //print_r($res);
                $entityClass::update($res['ID'],$arFieldUpdate);
          //      echo 'UPDATE: '.$res['ID'].' NAME: '.$arFieldUpdate['UF_NAME'].PHP_EOL;
            }
            else {
                $arFieldHLAdd = $this->priceMatrix[$i];
  //              print_r($this->priceMatrix[$i]);
                $entityClass::add($arFieldHLAdd);
                //echo $row;
                //echo 'ADD: '.$res['ID'].' NAME: '.$arFieldHLAdd['UF_NAME'].PHP_EOL;
            }
        }
    }
}
?>