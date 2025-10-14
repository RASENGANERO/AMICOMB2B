<?
namespace artdragon\stat;
\Bitrix\Main\Loader::registerAutoLoadClasses(
    'artdragon.stat', // Имя модуля
    [ // Класс => относительный путь от папки модуля
        'artdragon\\stat\\AddStatistics' => 'lib/AddStatistics.php',
        'artdragon\\stat\\StatisticsTable' => 'lib/StatisticsTable.php',
    ]
);
?>