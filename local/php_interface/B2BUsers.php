<?php
require_once("D:/OPENSERVER/domains/AMIKOMB2BNEW/local/lib/AmikomB2BRest/crest.php");
class B2BUsers {
    const USER_PARTNER_GROUP = 9;
    public static function setGroupRegUser(&$arFieldsUser) {
        if ($arFieldsUser['ID'] > 0) {
            if (!empty($arFieldsUser['UF_B2B_REGISTER'])) {
                $arGroups = CUser::GetUserGroup($arFieldsUser["ID"]); 
                $arGroups[] = self::USER_PARTNER_GROUP; 
                CUser::SetUserGroup($arFieldsUser["ID"], $arGroups);

                $data = [
                    'EMAIL' => $arFieldsUser['EMAIL'],
                    'PHONE' => $arFieldsUser['PERSONAL_PHONE'],
                    'NAME' => $arFieldsUser['NAME'],
                    'SECOND_NAME' => $arFieldsUser['SECOND_NAME'],
                    'LAST_NAME' => $arFieldsUser['LAST_NAME'],
                    'COMPANY' => $arFieldsUser['COMPANY'],
                    'INN' => $arFieldsUser['INN'],
                ];
                $IdContact = self::addContact($data);
                $IdCompany = self::addCompany($data,$IdContact);
                self::addLead($IdCompany,$IdContact,$data);
            }
        }
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
}
