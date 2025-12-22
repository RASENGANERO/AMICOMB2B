<?
namespace AmikomB2B;
use CIBlockElement;
class InformIblockB2B {
    const IBLOCK_COMPANY_B2B = 56;
    const IBLOCK_PARTNER_B2B = 54;
    const IBLOCK_TERMS_B2B = 57;
    public array $dataJS;
    public array $companyArr;
    public array $partnerManager;
    public array $termsArr;
    
    public string $guidPartner;
    function __construct($dataJsonArr)
    {
        $this->dataJS = $dataJsonArr;
        $this->companyArr = $dataJsonArr['_30_Contrag']['_p1'];
        $this->partnerManager = $dataJsonArr['_10_UsersManagers']['_p2'];
        $this->termsArr = $dataJsonArr['_40_TermsAgree']['_p1'];
        $this->guidPartner =  $dataJsonArr['_31_Рartner']['_p1']['Guid'];
    }
    public function setFieldsCompany():array {
        $propValues = [
            'INN' => $this->companyArr['INN'],
            'KPP' => $this->companyArr['KPP'],
            'PARTNER_B2B' => $this->guidPartner,
        ];
        $arFields = [
            'NAME' => $this->companyArr['NameFull'],
            'PROPERTY_VALUES' => $propValues,
            'ACTIVE' => 'Y',
            'SORT' => 500,
        ];
        return $arFields;
    }
    public function setFieldsPartner():array {
        $propValues = [
            'REGION' => $this->dataJS['_11_Region']['_p1']['Name'],
            'MANAGER' => $this->partnerManager['Guid'],
            'PARTNER_B2B' => $this->guidPartner,  
        ];
        $arFields = [
            'NAME' => $this->dataJS['_31_Рartner']['_p1']['NameFull'], 
            'PROPERTY_VALUES' => $propValues,
            'ACTIVE' => 'Y',
            'SORT' => 500,
        ];
        return $arFields;
    }
    public function setFieldsTerms():array {
        $dateTime = new \DateTime($this->termsArr['DateStart']);
        $formattedDate = $dateTime->format('d.m.Y H:i:s');
        $propValues = [
            'NUMBER' => $this->termsArr['Number'],
            'DATE_START' =>$formattedDate,
            'PARTNER_B2B' => $this->guidPartner,  
        ];
        $arFields = [
            'NAME' => $this->termsArr['Name'],
            'PROPERTY_VALUES' => $propValues,
            'ACTIVE' => 'Y',
            'SORT' => 500,
        ];
        return $arFields;
    }
    public function addElement($arAddFields,$idIBlock) {
        $elementAdd = new CIBlockElement;
        $arAddFields['IBLOCK_ID'] = $idIBlock;
        $arAddFields['CREATED_BY'] = 2;
        $arAddFields['MODIFIED_BY'] = 2;
        $elementAdd->Add($arAddFields);

    }
    public function updateElement($idElementUpdate,$arUpdateFields) {
        $elementUpdate = new CIBlockElement;
        $arUpdateFields['MODIFIED_BY'] = 2;
        $elementUpdate->Update($idElementUpdate,$arUpdateFields);
    }
    public function checkElement($IBLOCK_ID) {
        $filterElement = [
            'IBLOCK_ID' => $IBLOCK_ID,
            'PROPERTY_PARTNER_B2B' => $this->guidPartner,
        ];
        $checkElement = CIBlockElement::GetList(['SORT'=>'ASC'],$filterElement,false,false,['ID'])->Fetch();
        $arFields = [];
        switch ($IBLOCK_ID) {
            case self::IBLOCK_COMPANY_B2B:
                $arFields = $this->setFieldsCompany();
                break;
            case self::IBLOCK_PARTNER_B2B:
                $arFields = $this->setFieldsPartner();
                break;
            case self::IBLOCK_TERMS_B2B:
                $arFields = $this->setFieldsTerms();
                break;
        }
        if (!empty($checkElement)) {
            $this->updateElement($checkElement['ID'],$arFields);
        }
        else{
            $this->addElement($arFields,$IBLOCK_ID);
        }
    }
}

?>