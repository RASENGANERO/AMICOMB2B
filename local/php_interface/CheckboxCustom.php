<?php
CModule::IncludeModule("iblock");
use Bitrix\Main\Localization\Loc;
use CIBlockProperty;

Loc::loadMessages(__FILE__);
class CIBlockPropertyCheckbox extends CIBlockProperty
{
    const PROPERTY_TYPE = 'S';
    const USER_TYPE = 'checkbox';

    public static function GetUserTypeDescription(): array
    {
        return [
            'PROPERTY_TYPE' => self::PROPERTY_TYPE,
            'USER_TYPE' => self::USER_TYPE,
            'DESCRIPTION' => 'Чекбокс',
            'GetAdminListViewHTML' => [__CLASS__, 'getTextValue'],
            'GetPublicViewHTML' => [__CLASS__, 'getTextValue'],
            'GetPropertyFieldHtml' => [__CLASS__, 'getPropertyFieldHtml'],
            'GetPublicFilterHTML' => [__CLASS__, 'getFilterHTML'],
            'GetAdminFilterHTML' => [__CLASS__, 'getFilterHTML'],
            'ConvertToDB' => [__CLASS__, 'convertToDB'],
            'ConvertFromDB' => [__CLASS__, 'convertFromDB'],
            'GetLength' => [__CLASS__, 'getLength'],
        ];
    }

    public static function getTextValue($property, $value, $htmlControl): string
    {
        return $value['VALUE'] == 'Y' ? Loc::getMessage('checkbox_Y') : Loc::getMessage('checkbox_N');
    }

    public static function getPropertyFieldHtml($property, $value, $htmlControl): string
    {
        return '<input type="checkbox" name="' . $htmlControl['VALUE'] . '" value="Y" ' . ($value['VALUE'] == 'Y' ? 'checked="checked"' : '') . ' />';
    }

    public static function convertToDB($property, $value): array
    {
        return self::convertToFromDB($property, $value);
    }

    private static function convertToFromDB($property, $value): array
    {
        $value['VALUE'] = $value['VALUE'] == 'Y' ? 'Y' : 'N';
        return $value;
    }

    public static function convertFromDB($property, $value): array
    {
        return self::convertToFromDB($property, $value);
    }

    public static function getLength($property, $value): int
    {
        return 1;
    }
}
