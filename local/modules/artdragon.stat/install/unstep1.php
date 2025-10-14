<?if(!check_bitrix_sessid()) return;
use Bitrix\Main\Localization\Loc;
IncludeModuleLangFile(__FILE__);
echo CAdminMessage::ShowNote(Loc::getMessage('PARTNER_NAME'));
?>
<form action="<?=$APPLICATION->GetCurPage(); ?>">
	<?=bitrix_sessid_post(); ?>
	<input type="hidden" name="step" value="2">
	<input type="hidden" name="id" value="artdragon.stat">
	<input type="hidden" name="uninstall" value="Y">
	<p><b><?=GetMessage("SAVE_OR_DELETE_DATA")?>:</b><br>
	   <input type="radio" name="savedata" value="Y" CHECKED><?=GetMessage("SAVE")?><br>
	   <input type="radio" name="savedata" value="N"><?=GetMessage("DELETE_")?><br>
	</p>
	<input type="submit" name="nextstep" value="<?=GetMessage("NEXT")?>">
	<input type="submit" name="cancel" value="<?=GetMessage("CANCEL")?>">
</form>