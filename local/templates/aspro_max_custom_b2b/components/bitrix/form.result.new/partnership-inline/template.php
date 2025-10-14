<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
use \Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss('/local/templates/aspro_max_custom/components/bitrix/form.result.new/partnership/style.css');

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
		<div class="form_head">
			<? if ($arResult["isFormTitle"] == "Y"): ?>
				<h4 class="montazh-form-name"><?=$arResult["FORM_TITLE"]?></h4>
			<? endif; ?>
			<? if ($arResult["isFormDescription"] == "Y"): ?>
				<div class="form_desc">
					<?= $arResult["FORM_DESCRIPTION"] ?>
				</div>
			<? endif; ?>
		</div>
		
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
							<?else:?>
								<?CMax::drawFormField($FIELD_SID, $arQuestion);?>
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
					</span>
				</button>


				<!-- The Modal -->
				

				<script type="text/javascript">
					
					$(document).ready(function () {
						let modal = $("#modal-success");
						let span = $("#modal-success-close");
						let btn = $("#modal-success-btn-close");

						span.on('click', function() {
							modal.hide();
						});

						btn.on('click', function() {
							modal.hide();
						});

						$(window).on('click', function(event) {
							if ($(event.target).is(modal[0])) {
								modal.hide();
							}
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
		
		<!--/noindex-->
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