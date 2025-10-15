<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Пользователи");
$APPLICATION->AddChainItem("B2B Кабинет", "/b2b/");
$APPLICATION->AddChainItem("Пользователи", "/b2b/users/");
?>
<div class="dashboard-item">
    <h3 class="dashboard-maintext">Сотрудники</h3>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th scope="col">Статус</th>
                <th scope="col">Имя</th>
                <th scope="col">Email</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Куратор</td>
                <td>Менеджер Александр</td>
                <td>reshetnikov@am-trading.ru</td>
            </tr>
        </tbody>
    </table>
</div>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>