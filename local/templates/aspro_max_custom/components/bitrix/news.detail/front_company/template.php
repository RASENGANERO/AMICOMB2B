<?
use \Bitrix\Main\Localization\Loc;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();
$this->setFrameMode(true);

if($arResult['BENEFITS'])
{
	$templateData['BENEFITS'] = $arResult['DISPLAY_PROPERTIES']['LINK_BENEFIT']['VALUE'];
}
?>

<div class="content_wrapper_block <?=$templateName;?>">
	<div class="maxwidth-theme <?=$arParams['TYPE_BLOCK'] == 'type2' ? '' : 'wide'?> ">
		<?// preview image
		$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
		$bShowUrl = (isset($arResult['DISPLAY_PROPERTIES']['URL']) && strlen((string) $arResult['DISPLAY_PROPERTIES']['URL']['VALUE']));
		//echo '<pre>';
		//print_r($arResult);
		//echo '</pre>';
		$imageSrc = CFile::GetByID($arResult['PREVIEW_PICTURE']['ID'])->Fetch()['SRC'];
		

		$bNoImg = ($arParams['TYPE_IMG'] == 'sm no-img' && $arParams['TYPE_BLOCK'] == 'type2');

		$lightText = $arResult['BG_IMG'] && $arResult['PROPERTIES']['LIGHT_TEXT']['VALUE'] === 'Y';

		$videoSource = strlen((string) $arResult['PROPERTIES']['VIDEO_SOURCE']['VALUE_XML_ID']) ? $arResult['PROPERTIES']['VIDEO_SOURCE']['VALUE_XML_ID'] : 'LINK';
		$videoSrc = $arResult['PROPERTIES']['VIDEO_SRC']['VALUE'];
		if($videoFileID = $arResult['PROPERTIES']['VIDEO']['VALUE'])
			$videoFileSrc = CFile::GetPath($videoFileID);

		$videoPlayer = $videoPlayerSrc = '';
		if($videoSource == 'LINK' ? strlen((string) $videoSrc) : strlen((string) $videoFileSrc))
		{
			$bVideo = true;
			// $bVideoAutoStart = $arResult['PROPERTIES']['VIDEO_AUTOSTART']['VALUE_XML_ID'] === 'YES';
			if(strlen((string) $videoSrc) && $videoSource === 'LINK')
			{
				// videoSrc available values
				// YOTUBE:
				// https://youtu.be/WxUOLN933Ko
				// <iframe width="560" height="315" src="https://www.youtube.com/embed/WxUOLN933Ko" frameborder="0" allowfullscreen></iframe>
				// VIMEO:
				// https://vimeo.com/211336204
				// <iframe src="https://player.vimeo.com/video/211336204?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				// RUTUBE:
				// <iframe width="720" height="405" src="//rutube.ru/play/embed/10314281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>

				$videoPlayer = 'YOUTUBE';
				$videoSrc = htmlspecialchars_decode((string) $videoSrc);
				if(str_contains($videoSrc, 'iframe'))
				{
					$re = '/<iframe.*src=\"(.*)\".*><\/iframe>/isU';
					preg_match_all($re, $videoSrc, $arMatch);
					$videoSrc = $arMatch[1][0];
				}
				$videoPlayerSrc = $videoSrc;

				switch($videoSrc)
				{
					case(($v = strpos($videoSrc, 'vimeo.com/')) !== false):
						$videoPlayer = 'VIMEO';
						if(!str_contains($videoSrc, 'player.vimeo.com/'))
							$videoPlayerSrc = str_replace('vimeo.com/', 'player.vimeo.com/', $videoPlayerSrc);

						if(!str_contains($videoSrc, 'vimeo.com/video/'))
							$videoPlayerSrc = str_replace('vimeo.com/', 'vimeo.com/video/', $videoPlayerSrc);

						break;
					case(($v = strpos($videoSrc, 'rutube.ru/')) !== false):
						$videoPlayer = 'RUTUBE';
						break;
					case(str_contains($videoSrc, 'watch?') && ($v = strpos($videoSrc, 'v=')) !== false):
						$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 2, 11);
						break;
					case(str_contains($videoSrc, 'youtu.be/') && $v = strpos($videoSrc, 'youtu.be/')):
						$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 9, 11);
						break;
					case(str_contains($videoSrc, 'embed/') && $v = strpos($videoSrc, 'embed/')):
						$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 6, 11);
						break;
				}

				$bVideoPlayerYoutube = $videoPlayer === 'YOUTUBE';
				$bVideoPlayerVimeo = $videoPlayer === 'VIMEO';
				$bVideoPlayerRutube = $videoPlayer === 'RUTUBE';

				if(strlen($videoPlayerSrc))
				{
					$videoPlayerSrc = trim($videoPlayerSrc.
						($bVideoPlayerYoutube ? '?autoplay=1&enablejsapi=1&controls=0&showinfo=0&rel=0&disablekb=1&iv_load_policy=3' :
						($bVideoPlayerVimeo ? '?autoplay=1&badge=0&byline=0&portrait=0&title=0' :
						($bVideoPlayerRutube ? '?quality=1&autoStart=0&sTitle=false&sAuthor=false&platform=someplatform' : '')))
					);
				}
			}
			else
			{
				$videoPlayer = 'HTML5';
				$videoPlayerSrc = $videoFileSrc;
			}
		}
		?>

		<div class="item-views company lazy <?=$lightText ? 'company_light_text' : '';?> <?=$arParams['TYPE_IMG'];?> <?=$arParams['TYPE_BLOCK'];?><?=($arParams['TYPE_IMG'] == 'lg' ? ' ' : ' with-padding');?>" <?if($arResult['BG_IMG']):?>data-src="<?=$arResult['BG_IMG'];?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['BG_IMG']);?>)"<?endif;?>>
			<div class="company-block">
				<div class="row flexbox<?=($arParams['TYPE_BLOCK'] == 'type2' ? ($arParams['REVERCE_IMG_BLOCK'] == 'Y' ? '' : ' flex-direction-row-reverse') : ($arParams['REVERCE_IMG_BLOCK'] == 'Y' ? ' flex-direction-row-reverse' : ''))?>">
					<div class="col-md-<?=($arParams['TYPE_BLOCK'] == 'type2' ? ($arParams['TYPE_IMG'] == 'md' ? 8 : 9) : 6);?> text-block col-xs-12">
						<div class="item">
							<div class="item-inner">
								<div class="text">
									<?ob_start();?>
										<?if($bShowUrl):?>
											<a class="show_all muted font_upper" href="/about/">
										<?else:?>
											<span class="muted font_upper">
										<?endif;?>
										
										<?if(in_array('NAME', $arParams['FIELD_CODE']) && $arResult['FIELDS']['NAME']):?>
											<span><?=$arResult['FIELDS']['NAME']?></span>
										<?endif;?>

										<?if($bShowUrl):?>
											</a>
											<h2 class="top_block_title "><?=$arResult['PROPERTIES']['COMPANY_NAME']['VALUE']?></h2>
										<?else:?>
											</span>
										<?endif;?>
									<?$text = ob_get_contents();
									ob_end_clean();?>

									<?if(!$bNoImg):?>
										<?=$text;?>
									<?endif;?>

									<?if($arParams['REGION'] && $arParams['~REGION']['DETAIL_TEXT']):?>
										<div class="txt-top"><?=$arResult['PREVIEW_TEXT'];?></div>
									<?else:?>
										<?if($arResult['PREVIEW_TEXT']):?>
											<div class="preview-text muted777"><?=$arResult['PREVIEW_TEXT'];?></div>
										<?endif;?>
									<?endif;?>

									<?ob_start();?>
										<div class="buttons">
											<?if(isset($arResult['DISPLAY_PROPERTIES']['URL']) && strlen((string) $arResult['DISPLAY_PROPERTIES']['URL']['VALUE'])):?>
												<a class="btn <?=(!$bNoImg ? 'btn-default' : 'btn-transparent-border-color');?>" href="/about/"><span><?=(strlen((string) $arResult['PROPERTIES']['MORE_BUTTON_TITLE']['VALUE']) ? $arResult['PROPERTIES']['MORE_BUTTON_TITLE']['VALUE'] : Loc::getMessage('S_TO_SHOW_ALL_MORE'))?></span></a>
											<?endif;?>
										</div>
									<?$button = ob_get_contents();
									ob_end_clean();?>

									<?if($bNoImg):?>
										<?=$button;?>
									<?endif;?>

									<div class="js-tizers"></div>
									

									<?if(!$bNoImg):?>
										<?=$button;?>
									<?endif;?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-<?=($arParams['TYPE_BLOCK'] == 'type2' ? ($arParams['TYPE_IMG'] == 'md' ? 4 : 3) : 6);?> image-block col-xs-12">
						<div class="item video-block">
							<?if($bNoImg):?>
								<div class="with-text-block-wrapper">
									<?=$text;?>
									<div class="js-h2"></div>
									<?=$button;?>
								</div>
							<?elseif($imageSrc):?>
								<!--<div class="image lazy<?=($arParams['TYPE_BLOCK'] == 'type2' ? ' rounded' : '');?>" data-src="<?=$imageSrc;?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>)"  data-video_source="<?=$videoSource?>" <?=(strlen($videoPlayer) ? ' data-video_player="'.$videoPlayer.'"' : '')?><?=(strlen((string) $videoPlayerSrc) ? ' data-video_src="'.$videoPlayerSrc.'"' : '')?>>
									<?if($bVideo):?>
										<div class="play">
											<div class="fancy" rel="nofollow">
												<?if($videoPlayer == 'HTML5'):?>
													<template class="video">
														<video id="company_video" muted playsinline controls loop><source  class="video-block" data-src="<?=$videoPlayerSrc;?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' /></video>
													</template>
												<?else:?>
													<iframe class="video-block__iframe" data-src="<?=$videoPlayerSrc;?>"></iframe>
												<?endif;?>
											</div>
										</div>
									<?endif;?>
								</div>-->
								<div class="image lazy<?=($arParams['TYPE_BLOCK'] == 'type2' ? ' rounded' : '');?>">
									<div class="director-container">
										<img class="img-main-template" src="<?=$imageSrc?>"/>
										<div class="director-text-container">
											<span class="director-name">Елена Калягина</span>
											<span>основатель Амиком</span>
										</div>
									</div>
								</div>
							<?endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>