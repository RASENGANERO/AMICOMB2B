<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
use AmikomnewCRM;
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss('/local/templates/aspro_max_custom/components/bitrix/form.result.new/zakaz-inline/style.css');
?>

<? $frame = $this->createFrame()->begin('') ?>

<?
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
<div class="maxwidth-theme">
	<div class="form inline <?= $arResult["arForm"]["SID"] ?>">
		<!--noindex-->
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
		<!--/noindex-->
		
		<? if ($arParams['HIDE_SUCCESS'] != 'Y' || ($arParams['HIDE_SUCCESS'] == 'Y' && !strlen($arResult["FORM_NOTE"]))): ?>
			<?= $arResult["FORM_HEADER"] ?>
			<?= bitrix_sessid_post(); ?>
			<div class="form_body">
				<? if (is_array($arResult["QUESTIONS"])): ?>
					<? if (!$bLeftAndRight): ?>
						<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>

							<?if (str_starts_with(trim(strval($arQuestion['STRUCTURE'][0]['MESSAGE'])),'UTM') === true):?>
								<?
								\Amikomnew\CookieCRM::writeUTMField($arQuestion['STRUCTURE'][0]['MESSAGE'],$arQuestion['STRUCTURE'],$FIELD_SID);
								?>							
							<?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === 'radio'):?>
								<div class="form-control">
									<div class="form-custom-radio-main-container">
										<label>
											<span><?=$arQuestion['CAPTION']?></span>
										</label>
										<?
										$arStructures = $arQuestion['STRUCTURE'];
										?>
										<div class="form-custom-radio-container">
											<?
											foreach($arStructures as $itemStruct):
											?>
											<div class="filter form radio">
												<input type="radio" id="<?=$itemStruct['ID']?>" data-sid="<?=$FIELD_SID?>" name="form_radio_<?=$FIELD_SID?>" value="<?=$itemStruct['ID']?>"/>
												<label for="<?=$itemStruct['ID']?>">
													<span class="field-name"><?=$itemStruct['MESSAGE']?></span>
												</label>
											</div>
											<?endforeach;?>
										</div>
									</div>
								</div>
							<?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === 'dropdown'):?>
							
								<div class="form-control">
									<div class="form-custom-dropdown-main-container">
										<label>
											<span><?=$arQuestion['CAPTION']?></span>
										</label>
										<?
										$arStructures = $arQuestion['STRUCTURE'];
										?>
										<div class="form-custom-dropdown-container">
											<select class="inputselect" data-sid="<?=$FIELD_SID?>" name="form_dropdown_<?=$FIELD_SID?>" id="form_dropdown_<?=$FIELD_SID?>" aria-invalid="false">
												<?foreach($arStructures as $itemStruct):?>
													<option value="<?=$itemStruct['ID']?>"><?=$itemStruct['MESSAGE']?></option>
												<?endforeach;?>
											</select>
										</div>
									</div>	
								</div>
								<?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === 'text'):?>
									<?
									$checkField = $arQuestion['STRUCTURE'];
									?>
									<? if ($checkField[0]['VALUE'] === 'range'): ?>
										<div class="form-control">
											<div class="form-custom-multiselect-main-container">
												<label>
													<span><?=$arQuestion['CAPTION']?></span>
												</label>
												<?
												$arStructures = $arQuestion['STRUCTURE'];
												?>
												<div class="form-custom-multiselect-container">
													<?foreach($arStructures as $itemStruct):?>
														<?if(!empty($itemStruct['MESSAGE'])):?>
														<div class="numberinput-form-named">
															<label><span><?=$itemStruct['MESSAGE']?></span></label>
														<?endif;?>
															<div class="numberinput-form">
																<button type="button" class="form-numberinput-btn" data-attr="minus" data-ids="<?=$itemStruct['ID']?>">-</button>
																<input id="<?=$itemStruct['ID']?>" type="text" class="form-numberinput-inp inputtext" data-sid=<?=$FIELD_SID?>" name="form_text_<?=$itemStruct['ID']?>" value="0" readonly>
																<button type="button" class="form-numberinput-btn" data-attr="plus" data-ids="<?=$itemStruct['ID']?>">+</button>
															</div>
														<?if(!empty($itemStruct['MESSAGE'])):?>
														</div>
														<?endif;?>
													<?endforeach;?>
												</div>
											</div>	
										</div>
								<? else: ?>
									<? CMax::drawFormField($FIELD_SID, $arQuestion);?>
								<? endif; ?>
							<?else:?>
								<? CMax::drawFormField($FIELD_SID, $arQuestion);?>
							<?
							endif;
							?>
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
									<? if ($arQuestion["STRUCTURE"][0]["FIELD_PARAM"] != 'left'): ?>
										<? CMax::drawFormField($FIELD_SID, $arQuestion); ?>
									<? endif; ?>
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
				<button type="reset" class="btn btn-default white" value="reset" name="web_form_reset"><span>
						<?= GetMessage('FORM_RESET') ?>
					</span></button>
				<script type="text/javascript">
					$(document).ready(function () {
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

									let dtForm = new FormData(form);
									let object = Object.fromEntries(dtForm);
									console.log(object);
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
		
	</div>
</div>
<div id="modal-success" class="modal">
	<div class="modal-content">
		<span id="modal-success-close" class="close jqmClose">
			<i class="svg inline  svg-inline-" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
					<path data-name="Rounded Rectangle 114 copy 3" class="cccls-1" d="M334.411,138l6.3,6.3a1,1,0,0,1,0,1.414,0.992,0.992,0,0,1-1.408,0l-6.3-6.306-6.3,6.306a1,1,0,0,1-1.409-1.414l6.3-6.3-6.293-6.3a1,1,0,0,1,1.409-1.414l6.3,6.3,6.3-6.3A1,1,0,0,1,340.7,131.7Z" transform="translate(-325 -130)"></path>
				</svg>
			</i>
		</span>
		<div class="form_head">
			<h2><?=$arResult["FORM_TITLE"]?></h2>
		</div>
		<div class="form_result success">
			<div class="form_res-all-container">
				<div class="form_res-container">
					<i class="svg inline  svg-inline- colored" aria-hidden="true">
						<svg data-name="Group 252 copy" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50">
							<defs>
								<style>
									.cls-1,.cls-2{
										fill:#1d7ce6;
									}
									.cls-1{
										fill-rule:evenodd;
									}
									.cls-2{
										opacity:0.1;
									}
								</style>
							</defs>
							<path data-name="Rounded Rectangle 1004 copy 2" class="cls-1" d="M685,369a25,25,0,1,1,25-25A25,25,0,0,1,685,369Zm0-48a23,23,0,1,0,23,23A23,23,0,0,0,685,321Zm-2.18,29.553a1.032,1.032,0,0,1-1.55.135,0.881,0.881,0,0,1-.09-0.135l-5.869-5.857a1,1,0,0,1,1.414-1.416L682,348.543l11.275-11.263a1,1,0,0,1,1.414,1.416Z" transform="translate(-660 -319)"></path>
							<circle class="cls-2" cx="25" cy="25" r="19"></circle>
						</svg>
					</i>				
					<span class="success_text">Ваше сообщение успешно отправлено!</span>
				</div>
				<div class="close-btn-wrapper">
					<button id="modal-success-btn-close" class="btn btn-default btn-lg jqmClose has-ripple">Закрыть</button>
				</div>
			</div>
		</div>
	</div>
</div>
<? $frame->end() ?>