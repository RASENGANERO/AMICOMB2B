<?use CMax as Solution;?>
<?if($arResult["ID"]):?>
	<?
	CModule::IncludeModule('catalog');
	$dbRes = CCatalogStore::GetList(['ID' => 'ASC'], ['ID' => $arResult["ID"]], false, false, ["ID", "EMAIL", "UF_METRO", "UF_MORE_PHOTOS", "UF_PHONES"]);
	if($arStore = $dbRes->GetNext()){
		$arResult["EMAIL"] = htmlspecialchars_decode((string) $arStore["EMAIL"]);
		$arResult["MORE_PHOTOS"] = Solution::unserialize($arStore["UF_MORE_PHOTOS"]);
		$arResult["METRO_PLACEMARK_HTML"] = '';
		if($arResult["METRO"] = Solution::unserialize($arStore["~UF_METRO"])){
			foreach($arResult['METRO'] as $metro){
				$arResult["METRO_PLACEMARK_HTML"] .= '<div class="metro"><i></i>'.$metro.'</div>';
			}
		}
		$arStorePhones = is_array($arStore['~UF_PHONES'])
			? $arStore['~UF_PHONES']
			: (strlen((string) $arStore['~UF_PHONES']) ? Solution::unserialize($arStore['~UF_PHONES']) : []);
		$arResult["PHONE"] = array_merge(explode(",",(string) $arResult["PHONE"]), $arStorePhones);
	}
	?>
<?else:?>
	<?LocalRedirect(SITE_DIR.'contacts/');?>
<?endif;?>
