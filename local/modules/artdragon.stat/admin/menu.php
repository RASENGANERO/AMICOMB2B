<?
$MODULE_ID = "artdragon.stat";
$aMenu = [
   "parent_menu" => "global_menu_services",//global_menu_services, 
   "section" => $MODULE_ID."_Статистика 1С",
   "sort" => 50,
   "text" => "Статистика 1С (".$MODULE_ID.")",
   "title" => "Статистика 1С",
   "url" => "javascript:void(0)",
   "icon" => "fav_menu_icon",
   "page_icon" => "",
   "items_id" => $MODULE_ID."_items",
   "more_url" => [],
   "items" => []
];      
$aMenu["items"][] = [
    "text" => "Статистика элементов 1С",
    "url"  => $MODULE_ID."_"."addpage.php",
    "icon" => "form_menu_icon",
    "page_icon" => "form_page_icon",
    "more_url"  => [],
    "title" => "Статистика элементов 1С"
];

return $aMenu;