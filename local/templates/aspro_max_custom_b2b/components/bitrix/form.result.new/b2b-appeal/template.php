<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<? $frame = $this->createFrame()->begin('') ?>
<?
use AmikomB2B;

$userID = \AmikomB2B\DiscountInfo::getPartnerID($USER->GetID());
$companyName = \AmikomB2B\DataB2BUser::getCompany($userID)['NAME'];
$companyName = str_replace('"','',$companyName);
$userName = \AmikomB2B\DataB2BUser::getUserForm();
$bLeftAndRight = false;
if (is_array($arResult["QUESTIONS"])) {
	foreach ($arResult["QUESTIONS"] as $arQuestion) {
		if ($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] == 'left') {
			$bLeftAndRight = true;
			break;
		}
	}
}
?>
<div class="appeal-form maxwidth-theme">
	<div class="form inline <?= $arResult["arForm"]["SID"] ?>">
		
		<? if ($arParams['HIDE_SUCCESS'] != 'Y' || ($arParams['HIDE_SUCCESS'] == 'Y' && !strlen($arResult["FORM_NOTE"]))): ?>
			<?= $arResult["FORM_HEADER"] ?>
			<?= bitrix_sessid_post(); ?>
			<div class="form_body">
				<? if (is_array($arResult["QUESTIONS"])): ?>
					<? if (!$bLeftAndRight): ?>
						<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
							<?if ($FIELD_SID === 'USER_NAME'):?>
								<input type="hidden" data-sid="USER_NAME" name="form_hidden_309" value="<?=$userName?>">
							<?elseif ($FIELD_SID === 'USER_COMPANY'):?>
								<input type="hidden" data-sid="USER_COMPANY" name="form_hidden_310" value="<?=$companyName?>">
							<?else:?>
								<? CMax::drawFormField($FIELD_SID, $arQuestion); ?>
							<?endif;?>
						<? endforeach; ?>
					<? else: ?>
						<div class="row">
							<div class="col-md-7">
								
								<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
									<? if ($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] == 'left'): ?>
										<? CMax::drawFormField($FIELD_SID, $arQuestion); ?>
									<? endif; ?>
								<? endforeach; ?>
							</div>
							<div class="col-md-5">
								<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
									<?if (str_starts_with(trim(strval($arQuestion['STRUCTURE'][0]['MESSAGE'])),'UTM') === true):?>
									<?
									\Amikomnew\CookieCRM::writeUTMField($arQuestion['STRUCTURE'][0]['MESSAGE'],$arQuestion['STRUCTURE'],$FIELD_SID);
									?>
									<?else:?>
										<?CMax::drawFormField($FIELD_SID, $arQuestion);?>
									<?endif;?>
								<? endforeach; ?>
							</div>
						</div>
					<? endif; ?>
				<? endif; ?>
				<div class="clearboth"></div>
				<? $bHiddenCaptcha = (isset($arParams["HIDDEN_CAPTCHA"]) ? $arParams["HIDDEN_CAPTCHA"] : COption::GetOptionString("aspro.max", "HIDDEN_CAPTCHA", "Y")); ?>
				<? if ($arResult["isUseCaptcha"] == "Y"): ?>
					<div class="form-control captcha-row clearfix">
						<label><span>
								<?= GetMessage("FORM_CAPRCHE_TITLE") ?>&nbsp;<span class="star">*</span>
							</span></label>
						<div class="captcha_image">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>"
								border="0" />
							<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>" />
							<div class="captcha_reload"></div>
						</div>
						<div class="captcha_input">
							<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
						</div>
					</div>
				<? elseif ($bHiddenCaptcha == "Y"): ?>
					<textarea name="nspm" style="display:none;"></textarea>
				<? endif; ?>
				<div class="clearboth"></div>
			</div>
			<div class="form_footer">
				<? $bShowLicenses = (isset($arParams["SHOW_LICENCE"]) ? $arParams["SHOW_LICENCE"] : COption::GetOptionString("aspro.max", "SHOW_LICENCE", "Y")); ?>
				<? if ($bShowLicenses == "Y"): ?>
					<input type="hidden" name="aspro_max_form_validate" />
					<div class="licence_block filter onoff label_block">
						<input type="checkbox" id="licenses_inline" <?= (COption::GetOptionString("aspro.max", "LICENCE_CHECKED", "N") == "Y" ? "checked" : ""); ?> name="licenses_inline" required value="Y">
						<label for="licenses_inline">
							<? $APPLICATION->IncludeFile(SITE_DIR . "include/licenses_text.php", array(), array("MODE" => "html", "NAME" => "LICENSES")); ?>
						</label>
					</div>
				<? endif; ?>
				<button type="submit" class="btn btn-default" value="submit"><span>
						<?= $arResult["arForm"]["BUTTON"] ?>
					</span></button>
				<input type="hidden" class="btn btn-default" value="<?= $arResult["arForm"]["BUTTON"] ?>" name="web_form_submit">
				
				<script type="text/javascript">
					$(document).ready(function () {
						$('input[type=file]:not(".uniform-ignore")').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
						$(document).on('change', 'input[type=file]', function(){
							if($(this).val())
							{
								$(this).closest('.uploader').addClass('files_add');
							}
							else
							{
								$(this).closest('.uploader').removeClass('files_add');
							}
						})
						$('.form .add_file').on('click', function(){
							var index = $(this).closest('.input').find('input[type=file]').length+1;

							$(this).closest('.form-group').find('.input').append('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />');
							$('input[type=file]:not(".uniform-ignore")').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
						});

						$('.form .add_file').on('click', function(){
							var index = $(this).closest('.input').find('input[type=file]').length+1;

							$(this).closest('.form-group').find('.input').append('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />');
							$('input[type=file]:not(".uniform-ignore")').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
						});
						$('form[name="<?= $arResult["arForm"]["VARNAME"] ?>"]').validate({
							highlight: function (element) {
								$(element).parent().addClass('error');
							},
							unhighlight: function (element) {
								$(element).parent().removeClass('error');
							},
							submitHandler: function (form) {
								if ($('form[name="<?= $arResult["arForm"]["VARNAME"] ?>"]').valid()) {
									setTimeout(function () {
										$(form).find('button[type="submit"]').attr("disabled", "disabled");
									}, 500);
									var eventdata = { type: 'form_submit', form: form, form_name: '<?= $arResult["arForm"]["VARNAME"] ?>' };
									BX.onCustomEvent('onSubmitForm', [eventdata]);
								}
							},
							errorPlacement: function (error, element) {
								error.insertBefore(element);
							},
							messages: {
								licenses_inline: {
									required: BX.message('JS_REQUIRED_LICENSES')
								}
							}
						});

						if (arMaxOptions['THEME']['PHONE_MASK'].length) {
							var base_mask = arMaxOptions['THEME']['PHONE_MASK'].replace(/(\d)/g, '_');
							$('form[name=<?= $arResult["arForm"]["VARNAME"] ?>] input.phone, form[name=<?= $arResult["arForm"]["VARNAME"] ?>] input[data-sid=PHONE]').inputmask('mask', { 'mask': arMaxOptions['THEME']['PHONE_MASK'] });
							$('form[name=<?= $arResult["arForm"]["VARNAME"] ?>] input.phone, form[name=<?= $arResult["arForm"]["VARNAME"] ?>] input[data-sid=PHONE]').blur(function () {
								if ($(this).val() == base_mask || $(this).val() == '') {
									if ($(this).hasClass('required')) {
										$(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
									}
								}
							});
						}

						if (arAsproOptions['THEME']['DATE_MASK'].length) {
							$('.popup form[name="<?= $arResult["arForm"]["VARNAME"] ?>"] input.date').inputmask('datetime', {
								'inputFormat': arAsproOptions['THEME']['DATE_MASK'],
								'placeholder': arAsproOptions['THEME']['DATE_PLACEHOLDER'],
								'showMaskOnHover': false
							});
						}

						if (arAsproOptions['THEME']['DATETIME_MASK'].length) {
							$('.popup form[name="<?= $arResult["arForm"]["VARNAME"] ?>"] input.datetime').inputmask('datetime', {
								'inputFormat': arAsproOptions['THEME']['DATETIME_MASK'],
								'placeholder': arAsproOptions['THEME']['DATETIME_PLACEHOLDER'],
								'showMaskOnHover': false
							});
						}
						
					});
				</script>
			</div>
			<?= $arResult["FORM_FOOTER"] ?>
		<? else: ?>
			<script type="text/javascript">
				$(document).ready(function () {
					$('body, html').animate({ scrollTop: 0 }, 500);
				});
			</script>
		<? endif; ?>
		<? if ($arResult["isFormErrors"] == "Y" || strlen($arResult["FORM_NOTE"])): ?>
			<div class="form_result <?= ($arResult["isFormErrors"] == "Y" ? 'error' : 'success') ?>">
				<? if ($arResult["isFormErrors"] == "Y"): ?>
					<?= $arResult["FORM_ERRORS_TEXT"] ?>
				<? else: ?>
					<? $successNoteFile = SITE_DIR . "include/form/success_{$arResult["arForm"]["SID"]}.php"; ?>
					<? if (file_exists($_SERVER["DOCUMENT_ROOT"] . $successNoteFile)): ?>
						<? $APPLICATION->IncludeFile($successNoteFile, array(), array("MODE" => "html", "NAME" => "Form success note")); ?>
					<? else: ?>
						<?= GetMessage("FORM_SUCCESS"); ?>
					<? endif; ?>
					<script>
						if (arMaxOptions['THEME']['USE_FORMS_GOALS'] !== 'NONE') {
							var eventdata = { goal: 'goal_webform_success' + (arMaxOptions['THEME']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?= $arResult["arForm"]["ID"] ?>') };
							BX.onCustomEvent('onCounterGoals', [eventdata]);
						}
					</script>
				<? endif; ?>
			</div>
		<? endif; ?>
	</div>
</div>
<? $frame->end() ?>