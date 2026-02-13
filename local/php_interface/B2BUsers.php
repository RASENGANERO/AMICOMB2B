<?php

require_once("D:/OPENSERVER/domains/AMIKOMB2BNEW/local/lib/AmikomB2BRest/crest.php");
class B2BUsers {
    const USER_PARTNER_GROUP = 10;
    const MAIL_B2B_1C = '@ami-com.ru';
    public static function setGroupRegUser(&$arFieldsUser) {
        if ($arFieldsUser['ID'] > 0) {
            if (!empty($arFieldsUser['UF_B2B_REGISTER'])) {
                self::setGroupRegUser($arFieldsUser['ID']);
                self::setFieldCUser($arFieldsUser['ID'],$arFieldsUser['INN']);

                $data = [
                    'EMAIL' => $arFieldsUser['EMAIL'],
                    'PHONE' => $arFieldsUser['PERSONAL_PHONE'],
                    'NAME' => $arFieldsUser['NAME'],
                    'SECOND_NAME' => $arFieldsUser['SECOND_NAME'],
                    'LAST_NAME' => $arFieldsUser['LAST_NAME'],
                    'COMPANY' => $arFieldsUser['COMPANY'],
                    'INN' => $arFieldsUser['INN'],
                    '1C_EMAIL' => $arFieldsUser['INN'] . self::MAIL_B2B_1C
                ];
                $IdContact = self::addContact($data);
                $IdCompany = self::addCompany($data,$IdContact);
                self::addLead($IdCompany,$IdContact,$data);
                $arParamsSend = [
                    'SERVER_NAME' => $_SERVER['SERVER_NAME'],
                    'NAME' => $arFieldsUser['NAME'],
                    'LOGIN'=> $arFieldsUser['LOGIN'],
                    'PASS' => $arFieldsUser['CONFIRM_PASSWORD'],
                ];
                CEvent::SendImmediate('NEW_USER_B2B','s1',$arParamsSend,'Y','118');
            }
        }
    }
    public static function setGroupUser($idUser) {
        $arGroups = CUser::GetUserGroup($idUser); 
        $arGroups[] = self::USER_PARTNER_GROUP; 
        CUser::SetUserGroup($idUser, $arGroups);
    }
    public static function setFieldCUser($idUser, $INN) {
        $user = new CUser;
        $fieldsB2B = Array( 
            "UF_1C_MASTER_EMAIL" => $INN . self::MAIL_B2B_1C, 
        ); 
        $user->Update($idUser, $fieldsB2B);
    }
    public static function generateCommentCRM($dataComment) {
        $commentVal = '';
        $dataLabels = [
            'EMAIL' => 'Email',
            'PHONE' => 'Телефон',
            'NAME' => 'Имя',
            'SECOND_NAME' => 'Отчество',
            'LAST_NAME' => 'Фамилия',
            'COMPANY' => 'Компания',
            'INN' => 'ИНН',
            '1C_EMAIL' => 'Email в 1С'
        ];
        foreach ($dataLabels as $key => $value) {
            $commentVal.= $dataLabels[$key].' : '.$dataComment[$key]."\r\n";
        }
        return $commentVal;
    }
    public static function addLead($IdCompany,$IdContact,$data) {
        $params = [
            'FIELDS' => [
                'TITLE' => 'New Lead RestB2B', 
                'NAME' => $data['NAME'],//'Иван', 
                'LAST_NAME' => $data['LAST_NAME'],//'Петров', 
                'SECOND_NAME' => $data['SECOND_NAME'],//'Михалыч',
                'COMPANY_ID' => $IdCompany,
                'CONTACT_ID' => $IdContact,
                'EMAIL' => [
                    '0' => [
                        'VALUE' => $data['EMAIL'],//'mail@example.com', 
                        'VALUE_TYPE' => 'WORK', 
                    ], 
                ], 
                'PHONE' => [
                    '0' => [
                        'VALUE' => $data['PHONE'], 
                        'VALUE_TYPE' => 'WORK', 
                    ], 
                ], 
                'COMMENTS' => self::generateCommentCRM($data),
            ], 
        ];
        $resultLead = CRest::call(
                'crm.lead.add',
                $params		
            );
        return $resultLead;
    }
    public static function addContact($data):int {
        $params =  [
            'FIELDS' => [
                'NAME' => $data['NAME'],//'Иван',
                'SECOND_NAME' => $data['SECOND_NAME'],//'Иванович',
                'LAST_NAME' => $data['LAST_NAME'],//'Иванов',
                'SOURCE_ID' => 'WEB',
                'OPENED' => 'Y',
                'PHONE' => [
                    [
                        'VALUE' => $data['PHONE'],//'+7333333555',
                        'VALUE_TYPE' => 'WORK',
                    ],
                ],
                'EMAIL' => [
                    [
                        'VALUE' => $data['EMAIL'],//'ivanov@example.work',
                        'VALUE_TYPE' => 'WORK',
                    ]
                ],
            ]
        ];
        $resultContact = CRest::call(
            'crm.contact.add',
            $params	
        );
        return intval($resultContact['result']);
    }
    public static function addCompany($data,$IDContact):int {
        $params = [
            'fields' => [
                'TITLE' => $data['COMPANY'],//'ИП Титов',
                'OPENED' => 'Y',
                'ASSIGNED_BY_ID' => $IDContact,
                'PHONE' => [
                    [
                        'VALUE' => $data['PHONE'],//'555888', 
                        'VALUE_TYPE' => 'WORK'
                    ]
                ],
            ],
        ];
        $resultCompany = CRest::call(
            'crm.company.add',
            $params	
        );
        return intval($resultCompany['result']);
    }

    public static function blockMailMessage($arFields,$arTemplate) {
        $EventsMailB2B = [
            'NEW_USER',
        ];
        $checkB2BUrl = trim(strval($_SERVER['REQUEST_URI']));
        //file_put_contents('D:/OPENSERVER/domains/AMIKOMB2BNEW/local/php_interface/regCheck.txt',print_r($arFields,true),FILE_APPEND);
        //file_put_contents('D:/OPENSERVER/domains/AMIKOMB2BNEW/local/php_interface/regCheckTemplate.txt',print_r($arTemplate,true),FILE_APPEND);
        //file_put_contents('D:/OPENSERVER/domains/AMIKOMB2BNEW/local/php_interface/regServer.txt',print_r($_SERVER,true),FILE_APPEND);
    
        if (strpos($checkB2BUrl,'b2b') !== 0) {
            if (in_array($arTemplate['EVENT_NAME'],$EventsMailB2B) === true) {
                return false;
            }
        }
        
    }
    /*public static function blockUser(&$arParams) 
    { 
        file_put_contents('D:/OPENSERVER/domains/AMIKOMB2BNEW/local/php_interface/regCheck.txt',print_r($arParams,true),FILE_APPEND);
        //file_put_contents('D:/OPENSERVER/domains/AMIKOMB2BNEW/local/php_interface/regCheckTemplate.txt',print_r($arTemplate,true),FILE_APPEND);
        //file_put_contents('D:/OPENSERVER/domains/AMIKOMB2BNEW/local/php_interface/regServer.txt',print_r($_SERVER,true),FILE_APPEND);
    } */
    
}
