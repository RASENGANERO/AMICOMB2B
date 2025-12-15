<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(false);

global $arTheme;
?>
<div class="confirmation-page pk-page">
	<?//here you can place your own messages
	$class = match ($arResult['MESSAGE_CODE']) {
        //When user not found
        'E01' => 'alert-warning',
        //User was successfully authorized after confirmation
        'E02' => 'alert-success',
        //User already confirm his registration
        'E03' => 'alert-warning',
        //Missed confirmation code
        'E04' => 'alert-warning',
        //Confirmation code provided does not match stored one
        'E05' => 'alert-danger',
        //Confirmation was successfull
        'E06' => 'alert-success',
        //Some error occured during confirmation
        'E07' => 'alert-danger',
        default => 'alert-warning',
    };
	?>
	<?if($arResult['MESSAGE_TEXT'] <> ''):?>
		<?$text = str_replace(['<br>', '<br />'], "\n", $arResult['MESSAGE_TEXT']);?>
		<div class="alert <?=$class?> compact"><?=nl2br(htmlspecialcharsbx($text))?></div>
	<?endif;?>
	<?if($arResult['SHOW_FORM']):?>
		<div class="form">
			<form id="confirmation-page-form" method="post" action="<?=$arResult['FORM_ACTION']?>">
				<input type="hidden" name="<?=$arParams['USER_ID']?>" value="<?=$arResult['USER_ID']?>" />
				<div class="form_body">
					<div class="form-control">
						<label><span><?=GetMessage('CT_BSAC_LOGIN')?>&nbsp;<span class="star">*</span></label>
						<input type="text" name="<?=$arParams['LOGIN']?>" required maxlength="50" value="<?=(strlen((string) $arResult['LOGIN']) > 0 ? $arResult['LOGIN'] : $arResult['USER']['LOGIN'])?>" size="17" readonly />
					</div>
					<div class="form-control">
						<label><span><?=GetMessage('CT_BSAC_CONFIRM_CODE')?>&nbsp;<span class="star">*</span></label>
						<input type="text" name="<?=$arParams['CONFIRM_CODE']?>" required maxlength="50" value="<?=$arResult['CONFIRM_CODE']?>" size="17" />
					</div>
				</div>
				<div class="form_footer">
					<div class="line-block form_footer__bottom">
						<div class="line-block__item">
							<button class="btn btn-default btn-lg" type="submit" name="confirmation" value="Y"><span><?=GetMessage('CT_BSAC_CONFIRM')?></span></button>
						</div>
						<div class="line-block__item">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/required_message.php", [], ["MODE" => "html"]);?>
						</div>
					</div>
				</div>
			</form>
		</div>
	<?elseif(!$USER->IsAuthorized()):?>
		<?
		$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"main",
			[
				"REGISTER_URL" => SITE_DIR."auth/registration/",
				"PROFILE_URL" => SITE_DIR."auth/forgot-password/",
				"SHOW_ERRORS" => "Y"
			]
		);
		?>
	<?endif?>
	<script>
	$(document).ready(function(){
		$('#confirmation-page-form').validate({
			highlight: function(element){
				$(element).parent().addClass('error');
			},
			unhighlight: function(element){
				$(element).parent().removeClass('error');
			},
			submitHandler: function(form){
				if($(form).valid()){
					var $button = $(form).find('button[type=submit]');
					if($button.length){
						if(!$button.hasClass('loadings')){
		  					$button.addClass('loadings');

							var eventdata = {type: 'form_submit', form: form, form_name: 'CONFIRMATION'};
							BX.onCustomEvent('onSubmitForm', [eventdata]);
						}
		  			}
				}
			},
			errorPlacement: function(error, element){
				error.insertBefore(element);
			},
		});

		setTimeout(function(){
			$('#confirmation-page-form').find('input:visible').eq(0).focus();
		}, 50);
	});
	</script>
</div>