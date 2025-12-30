<?php
class B2BUsers {
    const USER_PARTNER_GROUP = 9;
    public static function setGroupRegUser(&$arFieldsUser) {
        if ($arFieldsUser['ID'] > 0) {
            if (!empty($arFieldsUser['UF_B2B_REGISTER'])) {
                $arGroups = CUser::GetUserGroup($arFieldsUser["ID"]); 
                $arGroups[] = self::USER_PARTNER_GROUP; //То добаляем пользователя в группу c ID15 
                CUser::SetUserGroup($arFieldsUser["ID"], $arGroups);
            }
        }
    }
}
