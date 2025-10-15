<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("B2B Кабинет");
$APPLICATION->AddChainItem("B2B Кабинет", "/b2b/");
?>
<section class="dashboard-section">
    <div class="dashboard-container">
        <div class="dashboard-item">
            <h3 class="dashboard-maintext">Соглашение</h3>
            <div class="dashboard-agreement-container">
                <span class="dashboard-agreement-item">2025-06-18</span>
                <span class="dashboard-agreement-item">МТ П-1806-01/2025</span>
                <span class="dashboard-agreement-item">Предоплата(МТ)СРВ-ТРЕЙД ООО(ЭДО)</span>
            </div>
        </div>
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
        <div class="dashboard-item">
            <h3 class="dashboard-maintext">Скидки</h3>
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Группа товаров</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Hikvision</td>
                        <td>47</td>
                    </tr>
                    <tr>
                        <td>Hik Non-CCTV</td>
                        <td>47</td>
                    </tr>
                    <tr>
                        <td>Hik TVI+NVR</td>
                        <td>47</td>
                    </tr>
                    <tr>
                        <td>Hik G0E</td>
                        <td>47</td>
                    </tr>
                    <tr>
                        <td>HiWatch</td>
                        <td>47</td>
                    </tr>
                    <tr>
                        <td>HiWatch Non-CCTV</td>
                        <td>47</td>
                    </tr>
                    <tr>
                        <td>Hiwatch Pro</td>
                        <td>47</td>
                    </tr>
                    <tr>
                        <td>Dahua</td>
                        <td>51</td>
                    </tr>
                    <tr>
                        <td>IFLOW</td>
                        <td>45</td>
                    </tr>
                    <tr>
                        <td>Tiandy SPARK</td>
                        <td>60</td>
                    </tr>
                    <tr>
                        <td>Tiandy SPARK AK</td>
                        <td>60</td>
                    </tr>
                    <tr>
                        <td>RVI N1</td>
                        <td>45</td>
                    </tr>
                    <tr>
                        <td>Бастион</td>
                        <td>25</td>
                    </tr>
                    <tr>
                        <td>ЦМО</td>
                        <td>25</td>
                    </tr>
                    <tr>
                        <td>Skynet</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td>Cabeus</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td>KadrOn</td>
                        <td>25</td>
                    </tr>
                    <tr>
                        <td>OSNOVO</td>
                        <td>30</td>
                    </tr>
                    <tr>
                        <td>Hyperline</td>
                        <td>30</td>
                    </tr>
                    <tr>
                        <td>TFortis</td>
                        <td>18</td>
                    </tr>
                    <tr>
                        <td>Hik IP</td>
                        <td>47</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>