<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle("Заголовок страницы 1");
global $DB;
use artdragon\stat\StatisticsTable;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
$table1C = "1c_statistics";

$moduleId = "artdragon.stat";
$sTableID = "tbl_stat"; // ID таблицы
$oSort = new CAdminSorting($sTableID, "ID_NUMBER", "asc"); // объект сортировки
$lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка

$orderSort = 'ASC';
$by = 'ID_NUMBER';
if (!empty($_GET['order']) && !empty($_GET['by'])) {
  $orderSort = $_GET['order'];
  $by = $_GET['by'];
}

// ******************************************************************** //
//                           ФИЛЬТР                                     //
// ******************************************************************** //
// *********************** CheckFilter ******************************** //
// проверку значений фильтра для удобства вынесем в отдельную функцию
// *********************** /CheckFilter ******************************* //
// опишем элементы фильтра
$FilterArr = [
  'find_element',
  'find_type_import',
  'find_date_from',
  'find_date_to',
];
// инициализируем фильтр
$lAdmin->InitFilter($FilterArr);
// если все значения фильтра корректны, обработаем его

// Проверка значений фильтра для удобства вынесем в отдельную функцию
function CheckFilter()
{
    global $FilterArr, $lAdmin;
    foreach ($FilterArr as $f) global $$f;

    return count($lAdmin->arFilterErrors) == 0; // Если ошибки есть, вернем false
}
$arFilter = [];


if (CheckFilter()) {
    // Создаем массив фильтрации для выборки StatisticsTable::getList()
    if (!empty($find_element)) {
        $arFilter['ID_ELEMENT'] = $find_element;
    }
    if (isset($_GET['find_type_import']) && intval($_GET['find_type_import']) !== 2) {
      $arFilter['TYPE'] = $find_type_import;
    }
    else{
      unset($arFilter['TYPE']);
    }
    if (isset($_GET['find_date_from'])) {
      $dateFrom = new \Bitrix\Main\Type\DateTime($find_date_from);
      $dateFrom->setTime(0,0,0,0);
      $arFilter['>=DATE_TIME_ELEMENT'] = new \Bitrix\Main\Type\DateTime($dateFrom);
    }
    if (isset($_GET['find_date_to'])) {
      $dateTo = new \Bitrix\Main\Type\DateTime($find_date_to);
      $dateTo->setTime(23,59,59,59);
      $arFilter['<=DATE_TIME_ELEMENT'] = $dateTo;
    }
}





// ******************************************************************** //
//                ДЕЙСТВИЯ НАД ЭЛЕМЕНТАМИ СПИСКА                        //
// ******************************************************************** //
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if ($_REQUEST['action_button'] === 'delete') {
    StatisticsTable::delete($_REQUEST['ID']);//$DB->Query("DELETE FROM ".$table1C." WHERE ID_NUMBER = ".$_REQUEST['ID'].";");
  }
}







// ******************************************************************** //
//                ВЫБОРКА ЭЛЕМЕНТОВ СПИСКА                              //
// ******************************************************************** //


//print_r($arFilter);
$rsData = StatisticsTable::getList([
    'order' => [
        $by => $orderSort, 
    ],
    'filter'=>$arFilter,
    
]);
$rsData = new CAdminResult($rsData, $sTableID);
// аналогично CDBResult инициализируем постраничную навигацию.
$rsData->NavStart();
// отправим вывод переключателя страниц в основной объект $lAdmin
$lAdmin->NavText($rsData->GetNavPrint('Рекомендованные товары'));

// ******************************************************************** //
//                ПОДГОТОВКА СПИСКА К ВЫВОДУ                            //
// ******************************************************************** //

//сформируем заголовки таблицы
$headers = []; 
$headers [] = [       
    'id' => 'ID_NUMBER',
    'content' => '№',
    'sort' => 'ID_NUMBER',
    'align' => 'right',
    'default' => true
]; 
$headers [] = [       
    'id' => 'ID_ELEMENT',
    'content' => 'ID элемента',
    'sort' => 'ID_ELEMENT',
    'align' => 'right',
    'default' => true
]; 
$headers [] = [       
    'id' => 'ELEMENT_CODE',
    'content' => 'Код элемента',
    'sort' => 'ELEMENT_CODE',
    'align' => 'right',
    'default' => true
]; 
$headers [] = [       
    'id' => 'ELEMENT_SECTION',
    'content' => 'Раздел элемента',
    'sort' => 'ELEMENT_SECTION',
    'align' => 'right',
    'default' => true
]; 
$headers [] = [       
    'id' => 'TYPE',
    'content' => 'Тип импорта',
    'sort' => 'TYPE',
    'align' => 'right',
    'default' => true
];
$headers [] = [       
    'id' => 'DATE_TIME_ELEMENT',
    'content' => 'Дата',
    'sort' => 'DATE_TIME_ELEMENT',
    'align' => 'right',
    'default' => true
];

$headers [] = [       
    'id' => 'OLD_PRICE',
    'content' => 'Старая цена',
    'sort' => 'OLD_PRICE',
    'align' => 'right',
    'default' => true
]; 
$headers [] = [       
    'id' => 'NEW_PRICE',
    'content' => 'Новая цена',
    'sort' => 'NEW_PRICE',
    'align' => 'right',
    'default' => true
]; 
$headers [] = [       
    'id' => 'URL_ELEMENT',
    'content' => 'URL элемента',
    'sort' => 'URL_ELEMENT',
    'align' => 'right',
    'default' => true
]; 

$lAdmin->AddHeaders($headers);


//получим данные для таблицы
while($arRes = $rsData->NavNext(true,"1с_")){  

  $row =& $lAdmin->AddRow($arRes['ID_NUMBER'], $arRes);
 
  if (intval($arRes['TYPE']) === 0) {
    $arRes['TYPE'] = 'Add'; 
  }
  else{
    $arRes['TYPE'] = 'Update';
  }
  
  
  $row->AddInputField('ID_NUMBER', ['size'=>20]);

  $row->AddInputField('ID_ELEMENT', ['size'=>20]);
  $row->AddViewField('ID_ELEMENT', '<a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=29&type=aspro_max_catalog&lang=ru&ID='.$arRes['ID_ELEMENT'].'&find_section_section=-1&WF=Y">'.$arRes['ID_ELEMENT'].'</a>');

  $row->AddInputField('ELEMENT_CODE', ['size'=>20]);
  $row->AddInputField('ELEMENT_SECTION', ['size'=>20]);
  $row->AddViewField('ELEMENT_SECTION', '<a target="_blank" href="'.$arRes['ELEMENT_SECTION'].'">'.$arRes['ELEMENT_SECTION'].'</a>');
  $row->AddInputField('TYPE', ['size'=>20]);
  $row->AddViewField('TYPE', '<span>'.$arRes['TYPE'].'</span>');
  // Выводим исходную строку даты
  $row->AddInputField('DATE_TIME_ELEMENT', ['size'=>20]);
  $dat = date('d.m.Y H:i:s',strtotime($arRes['DATE_TIME_ELEMENT']));
  $row->AddViewField('DATE_TIME_ELEMENT','<span>'.$dat.'</span>');

  $row->AddInputField('OLD_PRICE', ['size'=>20]);
  $row->AddViewField('OLD_PRICE', '<span>'.$arRes['OLD_PRICE'].' ₽'.'</span>');
  $row->AddInputField('NEW_PRICE', ['size'=>20]);
  $row->AddViewField('NEW_PRICE', '<span>'.$arRes['NEW_PRICE'].' ₽'.'</span>');
  $row->AddInputField('URL_ELEMENT', ['size'=>20]);
  $row->AddViewField('URL_ELEMENT', '<a target="_blank" href="'.$arRes['URL_ELEMENT'].'">'.$arRes['URL_ELEMENT'].'</a>');
  

  // сформируем контекстное меню
	$arActions = Array();
	// редактирование элемента
	$arActions[] = array(
		"ICON"=>"edit",
		"DEFAULT"=>true,
		"TEXT"=>Loc::getMessage("rub_edit"),
		"ACTION"=>$lAdmin->ActionRedirect("rubric_edit.php?ID=".$f_ID)
	);
  
	// удаление элемента
  $arActions[] = array(
    "ICON"=>"delete",
    "TEXT"=>Loc::getMessage('DELETE_ELEMENT'),
    "ACTION"=>"if(confirm('".Loc::getMessage('MESSAGE_DELETE_ELEMENT')."'))  ".$lAdmin->ActionDoGroup($arRes['ID_NUMBER'], 'delete')
  );
	// вставим разделитель
	$arActions[] = array("SEPARATOR"=>true);
	// проверка шаблона для автогенерируемых рассылок
	if (strlen($f_TEMPLATE)>0 && $f_AUTO=="Y")
  $arActions[] = array(
    "ICON"=>"",
    "TEXT"=>Loc::getMessage("rub_check"),
    "ACTION"=>$lAdmin->ActionRedirect("template_test.php?ID=".$f_ID)
  );
	// если последний элемент - разделитель, почистим мусор.
	if(is_set($arActions[count($arActions)-1], "SEPARATOR"))
		unset($arActions[count($arActions)-1]);
  
	// применим контекстное меню к строке
	$row->AddActions($arActions);

}

// резюме таблицы
$lAdmin->AddFooter(
  array(
    array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsData->SelectedRowsCount()), // кол-во элементов
    array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"), // счетчик выбранных элементов
  )
);



$aContext = array(
  array(
    "TEXT"=>GetMessage("ADD"),
    "LINK"=>$moduleId."_page2.php?lang=".LANG,
    "TITLE"=>GetMessage("ADD"),
    "ICON"=>"btn_new",
  ),
);

// и прикрепим его к списку
$lAdmin->AddAdminContextMenu($aContext);

// ******************************************************************** //
//                ВЫВОД                                                 //
// ******************************************************************** //

// альтернативный вывод
$lAdmin->CheckListMode();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>



<?
// ******************************************************************** //
//                ВЫВОД ФИЛЬТРА                                         //
// ******************************************************************** //
// создадим объект фильтра
$oFilter = new CAdminFilter(
  $sTableID . "_filter",
  array(
    Loc::getMessage('DATE_FROM'),
    Loc::getMessage('DATE_TO'),
  )
);

?>
<style>
  .adm-filter-item-left{
    font-weight: bold;
  }
</style>
<form name="find_form" method="get" action="<?php echo $APPLICATION->GetCurPage(); ?>">
  <?php
  $oFilter->Begin();
  ?>
  <tr>
    <td><b><?=Loc::getMessage('FILTER_ELEMENT')?></b></td>
    <td>
        <input type="text" size="25" name="find_element" value="<?php echo htmlspecialchars($find_element) ?>" title="<?= Loc::getMessage("rub_f_find_title") ?>">
    </td>
  </tr>
  
  <tr>
    <td><?=Loc::getMessage('FILTER_IMPORT')?></td>
    <td>
    <?php
        $arr = array(
            "reference" => array(
              "Любой",
              "Add",
              "Update",
            ),
            "reference_id" => array(
              "2",
              "0",
              "1",
            )
        );?>
       <?
        echo SelectBoxFromArray("find_type_import", $arr, $find_type_import, "", "");
        ?>
      </td>
  </tr>
  <tr>
    <td><?=Loc::getMessage('DATE_FROM')?></td>
    <td>
        <?
        echo CalendarDate("find_date_from", date("d.m.Y"), "find_form", "15", "class=\"my_input\"");
        ?>
      </td>
  </tr>

  <tr>
    <td><?=Loc::getMessage('DATE_TO')?></td>
    <td>
        <?
        echo CalendarDate("find_date_to", date("d.m.Y"), "find_form", "15", "class=\"my_input\"");
        ?>
      </td>
  </tr>
  <?php
  $oFilter->Buttons(array("table_id" => $sTableID, "url" => $APPLICATION->GetCurPage(), "form" => "find_form"));
  $oFilter->End();
  ?>
</form>
<?
$lAdmin->DisplayList();
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>