<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация B2B");
?><?$APPLICATION->IncludeComponent(
  "aspro:auth.max", 
  "main", 
  array(
    "SEF_MODE" => "Y",
    "SEF_FOLDER" => "/b2b/auth/",
    "SEF_URL_TEMPLATES" => array(
      "auth" => "",
      "registration" => "registration/",
      "forgot" => "forgot-password/",
      "change" => "change-password/",
      "confirm" => "confirm-password/",
      "confirm_registration" => "confirm-registration/",
    ),
    "PERSONAL" => "/b2b/personal/",
    "SUCCESS_REDIRECT" => "/b2b/", // Параметр для редиректа
  ),
  false
);?><?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>