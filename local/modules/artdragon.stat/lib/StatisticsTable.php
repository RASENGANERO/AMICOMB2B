<?
namespace artdragon\stat;

// пространство имен для ORM
use \Bitrix\Main\Entity;

class StatisticsTable extends Entity\DataManager 
{
    public static function getTableName() {
        return '1c_statistics';
    }
    public static function getConnectionName() {
        return 'default';
    }
    public static function getMap() {
        return [
            new Entity\IntegerField('ID_NUMBER', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\IntegerField('ID_ELEMENT', [
                'required' => true,
            ]),
            new Entity\StringField('ELEMENT_CODE', [
                'required' => true,
            ]),
            new Entity\StringField('ELEMENT_SECTION', [
                'required' => true,
            ]),
            new Entity\BooleanField('TYPE', [
                'required' => true,
            ]),
            new Entity\DatetimeField('DATE_TIME_ELEMENT', [
                'required' => true,
            ]),
            new Entity\FloatField('OLD_PRICE'),
            new Entity\FloatField('NEW_PRICE'),
            new Entity\StringField('URL_ELEMENT', [
                'required' => true,
            ]),
        ];
    }
}

?>