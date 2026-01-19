<?php
class B2BUsers {
    const USER_PARTNER_GROUP = 9;
    public static function setGroupRegUser(&$arFieldsUser) {

        if ($arFieldsUser['ID'] > 0) {
            file_put_contents('D:/OPENSERVER/domains/AMIKOMB2BNEW/local/php_interface/b2buser.txt',print_r($arFieldsUser,true),FILE_APPEND);
            if (!empty($arFieldsUser['UF_B2B_REGISTER'])) {
                $arGroups = CUser::GetUserGroup($arFieldsUser["ID"]); 
                $arGroups[] = self::USER_PARTNER_GROUP; //То добаляем пользователя в группу c ID15 
                CUser::SetUserGroup($arFieldsUser["ID"], $arGroups);


                
            }
        }
    }
    public static function addLead() {
        $params = ['FIELDS' => [
            'TITLE' => 'New Lead RestB2B', 
            'NAME' => 'Иван', 
            'LAST_NAME' => 'Петров', 
            'EMAIL' => [
                '0' => [
                    'VALUE' => 'mail@example.com', 
                    'VALUE_TYPE' => 'WORK', 
                ], 
            ], 
            'PHONE' => [
                '0' => [
                    'VALUE' => '555888', 
                    'VALUE_TYPE' => 'WORK', 
                ], 
            ], 
        ], 
        ];
        $result = CRest::call(
                'crm.lead.add',
                $params		
            );
        return $result;
    }
    public static function addContact() {

    }
    public static function addCompany() {

    }
    public static function setContactLead() {

    }
}
