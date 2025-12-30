<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Организации");
?>
<?
if (!$USER->IsAuthorized()) {
    LocalRedirect('/404.php');
}
?>
<div class="dashboard-item">
    <h3 class="dashboard-maintext">Профили организаций</h3>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Компания</th>
                <th>ИНН</th>
                <th>КПП</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>СРВ-ТРЕЙД ООО</td>
                <td>ООО "СРВ-ТРЕЙД"</td>
                <td>5052019178</td>
                <td>505001001</td>
            </tr>
        </tbody>
    </table>
</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>