<?

use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;

// если администратор
if($USER->IsAdmin() && CModule::IncludeModule('artdragon.stat')):

	$request = Context::getCurrent()->getRequest(); // формируем объект запроса

	// если переданы данные из формы
	if($request->getRequestMethod() == 'POST' && !empty($request->getPost('apply')) && check_bitrix_sessid()){

        Option::set("artdragon.stat", "TEST_OPTION1", $request->getPost("TestOption1"));
        Option::set("artdragon.stat", "TEST_OPTION2", $request->getPost("TestOption2"));

	}

	// табы
	$arTabs = array(
		array(
			'DIV' => 'MainSettings',
			'TAB' => 'Первая вкладка',
			'TITLE' => 'Основные настройки Тестового модуля'

		),
		array(
			'DIV' => 'SecondSettings',
			'TAB' => 'Вторая вкладка',
			'TITLE' => 'Дополнительные настройки Тестового модуля'

		),
	);		

	// инициализируем вывод табов
	$tabControl = new CAdminTabControl('tabControl', $arTabs);
	$tabControl->Begin();
	?>

	<form method="post" name="webhooks" action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?=urlencode($mid)?>&amp;lang=<? echo LANGUAGE_ID ?>">
		<?=bitrix_sessid_post();?>

		<?$tabControl->BeginNextTab();?>
        <tr>

            <td valign="middle" width="40%" class="adm-detail-content-cell-l">
              опция 1
            </td>

            <td valign="middle" width="60%" class="adm-detail-content-cell-r">
            	<?$arOptionVariants = [1,2,3]?>

                <select name="TestOption1" >
                    <?foreach ($arOptionVariants as $option):?>
                        <option <?=($option ==  Option::get("artdragon.stat", "TEST_OPTION1"))?"selected":""?> value="<?=$option?>"><?=$option?></option>
                    <?endforeach;?>
                </select>
            </td>
        </tr>
        <?$tabControl->BeginNextTab();?>
        <tr>
            <td valign="middle" width="40%" class="adm-detail-content-cell-l">
                опция 2
            </td>
            <td valign="middle" width="60%" class="adm-detail-content-cell-r">
                <input value="<?=Option::get("artdragon.stat", "TEST_OPTION2")?>" type="text" name="TestOption2" style="width: 50px;">
            </td>

        </tr>
		<?$tabControl->Buttons();
		bitrix_sessid_post();
		?>
		<input type="submit" name="apply" value="<?=GetMessage('MAIN_SAVE')?>" title="<?=GetMessage('MAIN_OPT_SAVE_TITLE')?>" class="adm-btn-save">
		<?$tabControl->End();?>
		</form>
<?endif;