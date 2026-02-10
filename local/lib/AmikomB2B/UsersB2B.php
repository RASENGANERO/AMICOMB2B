<?

namespace AmikomB2B;
use CUser;
class UsersB2B {
    public array $dataJS;
    public array $userB2B;
    public string $guidPartner;
    public string $email1C;
    function __construct($dataJsonArr)
    {
        $this->dataJS = $dataJsonArr;
        $this->userB2B = $dataJsonArr['_31_Рartner']['_p1'];
        $this->email1C = $this->getEmail1C();
        $this->guidPartner =  $dataJsonArr['_31_Рartner']['_p1']['Guid'];
    }

    public function updatePartnerIDUser($ID_B2B_USER) {
        $user = new CUser;
        $arFiledsRegB2B = [
            'UF_PARTNER_ID' => $this->guidPartner
        ];
        $user->Update($ID_B2B_USER,$arFiledsRegB2B);
    }
    public function checkPartnerIDUser() {
        $arFilter = [
            'UF_1C_MASTER_EMAIL' => $this->email1C    
        ];
        $arFields = [
            'ID',
            'UF_1C_MASTER_EMAIL',
            'UF_PARTNER_ID'
        ];
        $res = CUser::GetList('sort','asc', $arFilter,$arFields)->Fetch();
        if (!empty($res)) {
            $rsUser = CUser::GetByID($res['ID'])->Fetch();
            if ((!empty($rsUser['UF_1C_MASTER_EMAIL'])) && (empty($rsUser['UF_PARTNER_ID']))) {
                $this->updatePartnerIDUser(intval($rsUser['ID']));
            }
        }
    }
    public function getEmail1C() {
        $email = trim(strval($this->userB2B['ClientMasterEmail']));
        if (intval(strpos("~",strval($email))) !== false) {
            $email = explode("~",$email);
            $email = end($email);
            $email = trim(strval($email));
        }
        return $email;
    }
    
}

?>