<?
	if (is_array($arResult["GRID"]["ROWS"]))
	{
		usort($arResult["GRID"]["ROWS"], CMax::cmpByID(...));
		$arImages = [];
		$link_services = [];
		foreach($arResult["GRID"]["ROWS"] as $key=>$arItem)
		{
			// fix bitrix measure bug
			if(!isset($arItem["MEASURE"]) && !isset($arItem["MEASURE_RATIO"]) && strlen((string) $arItem["MEASURE_TEXT"])){
				$arResult["GRID"]["ROWS"][$key]["MEASURE_RATIO"] = 1;
			} 

			//fix image size
			if (isset($arItem["PREVIEW_PICTURE"]) && intval($arItem["PREVIEW_PICTURE"]) > 0)
			{	
				$arImage = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);
				if ($arImage)
				{
					$arFileTmp = CFile::ResizeImageGet( $arImage, ["width" => $arParams["PICTURE_WIDTH"], "height" =>$arParams["PICTURE_HEIGHT"]], BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$picture = [];
					foreach($arFileTmp as $name => $value) { $picture[strToUpper($name)] = $value; }
					$arResult["GRID"]["ROWS"][$key]["PREVIEW_PICTURE"]  = $picture;
				}
			}
			if (isset($arItem["DETAIL_PICTURE"]) && intval($arItem["DETAIL_PICTURE"]) > 0)
			{
				$arImage = CFile::GetFileArray($arItem["DETAIL_PICTURE"]);
				if ($arImage)
				{
					$arFileTmp = CFile::ResizeImageGet($arImage, ["width" => $arParams["PICTURE_WIDTH"], "height" =>$arParams["PICTURE_HEIGHT"]], BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$picture = [];
					foreach($arFileTmp as $name => $value) { $picture[strToUpper($name)] = $value; }
					$arResult["GRID"]["ROWS"][$key]["DETAIL_PICTURE"]  = $picture;
				}
			}
			if(str_contains((string) $arItem["PRODUCT_XML_ID"], "#")){
				$arXmlID=explode("#", (string) $arItem["PRODUCT_XML_ID"]);
				$arItem1 =CMaxCache::CIBLockElement_GetList(['CACHE' => ["MULTI"=>"N", "TAG" => CMaxCache::GetIBlockCacheTag($arItem["IBLOCK_ID"])]], ["IBLOCK_ID" => $arItem["IBLOCK_ID"], "ACTIVE"=>"Y", "ACTIVE_DATE" => "Y", "XML_ID" => $arXmlID[0]], false, false, ["ID", "IBLOCK_ID"]);
				$arResult["GRID"]["ROWS"][$key]["IBLOCK_ID"]=$arItem1["IBLOCK_ID"];
				$arResult["ITEMS_IBLOCK_ID"]=$arItem1["IBLOCK_ID"];
			}

			/*fill buy services array */
			if($arItem["PROPS"]){
				$arPropsByCode = array_column($arItem["PROPS"], NULL , "CODE");
				$isServices = isset($arPropsByCode["ASPRO_BUY_PRODUCT_ID"]) && $arPropsByCode["ASPRO_BUY_PRODUCT_ID"]["VALUE"]>0;
				$services_info = [];
				if($isServices){
					$arResult["GRID"]["BUY_SERVICES"]['SERVICES'][$arItem["ID"]] = $arPropsByCode["ASPRO_BUY_PRODUCT_ID"]["VALUE"];
					$services_info['BASKET_ID'] = $arItem["ID"];
					$services_info['PRODUCT_ID'] = $arItem["PRODUCT_ID"];
					$services_info['QUANTITY'] = $arItem["QUANTITY"];
					$services_info['PRICE_FORMATED'] = $arItem["PRICE_FORMATED"];
					$services_info['FULL_PRICE_FORMATED'] = $arItem["FULL_PRICE_FORMATED"];
					$services_info['SUM_FORMATED'] = $arItem["SUM"];
					$services_info['SUM_FULL_PRICE_FORMATED'] = $arItem["SUM_FULL_PRICE_FORMATED"];
					$services_info['NEED_SHOW_OLD_SUM'] = $arItem["SUM_DISCOUNT_PRICE"] > 0 ? 'Y' : 'N';
					$services_info['CURRENCY'] = $arItem["CURRENCY"];
					$link_services[$arPropsByCode["ASPRO_BUY_PRODUCT_ID"]["VALUE"]][$arItem["PRODUCT_ID"]] = $services_info;
				}
			}
			/**/
		}		

		foreach($arResult["GRID"]["ROWS"] as $key=>$arItem)
		{
			if ($arImages[$key]["PREVIEW_PICTURE"]) {$arResult["GRID"]["ROWS"][$key]["PREVIEW_PICTURE"] = $arImages[$key]["PREVIEW_PICTURE"];}
			if ($arImages[$key]["DETAIL_PICTURE"]) {$arResult["GRID"]["ROWS"][$key]["DETAIL_PICTURE"] = $arImages[$key]["DETAIL_PICTURE"];}
			$symb = substr((string) $arItem["PRICE_FORMATED"], strrpos((string) $arItem["PRICE_FORMATED"], ' '));
			//if((int)$symb){
				$arResult["GRID"]["ROWS"][$key]["SUMM_FORMATED"]=$arItem["SUM"];
			/*}else{
				$arResult["GRID"]["ROWS"][$key]["SUMM_FORMATED"] = str_replace($symb, "", FormatCurrency($arItem["PRICE"]*$arItem["QUANTITY"], $arItem["CURRENCY"])).$symb;
			}*/

			/*fill link services add to cart*/
			if(count($link_services) > 0){	
				//var_dump($link_services[$arItem["PRODUCT_ID"]]);
				if( isset($link_services[$arItem["PRODUCT_ID"]]) ){
					$arResult["GRID"]["ROWS"][$key]["LINK_SERVICES"] = $link_services[$arItem["PRODUCT_ID"]];
				}
			}
			/**/
		}
		unset($arImages);
		
		

		$isPrice = false;
		$priceIndex = 0;
		foreach($arResult["GRID"]["HEADERS"] as $key => $arHeader)
		{
			if($arHeader["id"]=="PRICE") 
			{
				$isPrice = true; 
				$priceIndex = $key;
			}
		}
		
		foreach($arResult["GRID"]["HEADERS"] as $key => $arHeader)
		{
				if ($arHeader["id"]=="QUANTITY" && $isPrice && $priceIndex)
				{
					$arResult["GRID"]["HEADERS"] = array_merge(	array_slice($arResult["GRID"]["HEADERS"], 0, $priceIndex), 
								[["id"=>"SUMM", "name"=>""]], 
								array_slice($arResult["GRID"]["HEADERS"], $priceIndex, count($arResult["GRID"]["HEADERS"]))
							);
				}
		}
					
		foreach($arResult["GRID"]["HEADERS"] as $key => $arHeader)
		{
			$arResult["GRID"]["HEADERS"][$key]["SORT"] = match ($arHeader["id"]) {
                "DELETE" => 100,
                "NAME" => 200,
                "DISCOUNT" => 300,
                "PROPS" => 400,
                "WEIGHT" => 500,
                "PRICE" => 600,
                "QUANTITY" => 700,
                "SUMM" => 800,
                "DELAY" => 1000,
                default => 900,
            };

			if($arHeader["id"] == "PREVIEW_PICTURE")
				unset($arResult["GRID"]["HEADERS"][$key]);

		}
		usort($arResult["GRID"]["HEADERS"], CMax::cmpBySort(...));
		
		
		$arNormal = [];
		$arDelay = [];
		$arSubscribe = [];
		$arNa = [];	
		$arTotals = [];
		$arResult["DELAY_PRICE"]["SUMM"]=$arResult["SUBSCRIBE_PRICE"]["SUMM"]=$arResult["NA_PRICE"]["SUMM"]=0;
			
		foreach ($arResult["GRID"]["ROWS"] as $arItem)
		{
			if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y")
			{
				$arNormal[$arItem["ID"]] = $arItem;  
			}
			if ($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "Y")
			{
				$arDelay[$arItem["ID"]] = $arItem;
				$arResult["DELAY_PRICE"]["SUMM"]+=$arItem["PRICE"]*$arItem["QUANTITY"];
				
			}
			if ($arItem["CAN_BUY"] == "N" && $arItem["SUBSCRIBE"] == "Y")
			{
				$arSubscribe[$arItem["ID"]] = $arItem;
				$arResult["SUBSCRIBE_PRICE"]["SUMM"]+=$arItem["PRICE"]*$arItem["QUANTITY"];
			}
			if (isset($arItem["NOT_AVAILABLE"]) && $arItem["NOT_AVAILABLE"] == true)
			{
				$arNa[$arItem["ID"]] = $arItem;
				$arResult["NA_PRICE"]["SUMM"]+=$arItem["PRICE"]*$arItem["QUANTITY"];
			}

			
		}
		
		$bWeightColumn = false;

		foreach ($arResult["GRID"]["HEADERS"] as $arHeader) {
			if ($arHeader["id"] == "WEIGHT") {
				$bWeightColumn = true;
				break;
			}
		}
		 
		if ($bWeightColumn) {
			$arTotal["WEIGHT"]["NAME"] = GetMessage("SALE_TOTAL_WEIGHT");
			$arTotal["WEIGHT"]["VALUE"] = $arResult["allWeight_FORMATED"];
		}
		if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") 
		{ 
			$arTotal["VAT_EXCLUDED"]["NAME"] = GetMessage("SALE_VAT_EXCLUDED"); $arTotal["VAT_EXCLUDED"]["VALUE"] = $arResult["allSum_wVAT_FORMATED"];
			$arTotal["VAT_INCLUDED"]["NAME"] = GetMessage("SALE_VAT_INCLUDED"); $arTotal["VAT_INCLUDED"]["VALUE"] = $arResult["allVATSum_FORMATED"];
		}
		if (doubleval($arResult["DISCOUNT_PRICE_ALL"]) > 0)
		{
			$arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL"); 
			$arTotal["PRICE"]["VALUES"]["ALL"] = str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"]);
			$arTotal["PRICE"]["VALUES"]["WITHOUT_DISCOUNT"] = $arResult["PRICE_WITHOUT_DISCOUNT"];
		}
		else
		{
			$arTotal["PRICE"]["NAME"] = GetMessage("SALE_TOTAL"); 
			$arTotal["PRICE"]["VALUES"]["ALL"] = $arResult["allSum_FORMATED"];
		}

		$arNormal["COUNT"] = count($arNormal);
		$arNormal["TOTAL"] = $arTotal;
		
		$arDelay["COUNT"] = count($arDelay);
		$arSubscribe["COUNT"] = count($arSubscribe);
		$arNa["COUNT"] = count($arNa);

		if($arResult["DELAY_PRICE"]["SUMM"])
			$arResult["DELAY_PRICE"]["SUMM_FORMATED"]=CCurrencyLang::CurrencyFormat($arResult["DELAY_PRICE"]["SUMM"], CSaleLang::GetLangCurrency(SITE_ID), true);

		if($arResult["SUBSCRIBE_PRICE"]["SUMM"])
			$arResult["SUBSCRIBE_PRICE"]["SUMM_FORMATED"]=CCurrencyLang::CurrencyFormat($arResult["SUBSCRIBE_PRICE"]["SUMM"], CSaleLang::GetLangCurrency(SITE_ID), true);

		if($arResult["NA_PRICE"]["SUMM"])
			$arResult["NA_PRICE"]["SUMM_FORMATED"]=CCurrencyLang::CurrencyFormat($arResult["NA_PRICE"]["SUMM"], CSaleLang::GetLangCurrency(SITE_ID), true);
		
		$arJson = [];
		if ($arNormal["COUNT"]) { $arJson[]= ["AnDelCanBuy"=>$arNormal]; }
		if ($arDelay["COUNT"]) { $arJson[]= ["DelDelCanBuy"=>$arDelay]; }
		if ($arSubscribe["COUNT"]) { $arJson[]= ["ProdSubscribe"=>$arSubscribe]; }
		if ($arNa["COUNT"]) { $arJson[]= ["nAnCanBuy"=>$arNa]; }
		
		$arResult["JSON"] = $arJson;
	}
?>