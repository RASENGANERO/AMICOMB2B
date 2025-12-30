<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация B2B");
?>

<?$APPLICATION->IncludeComponent("aspro:auth.max", "main-b2b", array(
	"SEF_MODE" => "Y",
	"SEF_FOLDER" => "/b2b/",
	"SEF_URL_TEMPLATES" => array(
		"auth" => "",
		"registration" => "auth/registration/",
		"forgot" => "forgot-password/",
		"change" => "change-password/",
		"confirm" => "confirm-password/",
		"confirm_registration" => "confirm-registration/",
	),
	"PERSONAL" => "/b2b/"
	),
	false
);?><?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>