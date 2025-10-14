<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("Карта сайта");?>
<?
use Amikomnew\SubsectionsTree;
$sectionsArr = SubsectionsTree::getSections();
//echo "<pre>";
//print_r($sectionsArr);
//echo "</pre>";
?>
<?

function renderCategories($items, $checkFirst) {
    if ($checkFirst === true) {
        echo '<div class="main-map-list">';
    } else {
        echo '<div class="main-map-contain">';
    }

    foreach ($items as $item) {
        // Создаём контейнер для каждого элемента
        echo '<div class="map-item">';

        if (intval($item['ROOT']) === 0) {
            echo '<a class="main-map-text" href="'.$item['URL'].'">' . htmlspecialchars($item['NAME']) . '</a>';
        } else {
            // Если это не корневой элемент, выводим его имя в li
            echo '<li class="map-li-element"><a class="map-url-element" href="'.$item['URL'].'">' . htmlspecialchars($item['NAME']) . '</a></li>';
        }

        // Проверяем наличие дочерних элементов
        if (!empty($item['CHILDREN'])) {
            echo '<ul class="map-list-children">'; // Открываем ul для дочерних элементов
            renderCategories($item['CHILDREN'], false); // Рекурсивный вызов для дочерних элементов
            echo '</ul>'; // Закрываем ul
        }

        echo '</div>'; // Закрываем контейнер для текущего элемента
    }

    if ($checkFirst === true) {
        echo '</div>'; // Закрываем общий контейнер
    } else {
        echo '</div>'; // Закрываем контейнер для дочерних элементов
    }
}

// Вызов функции
renderCategories($sectionsArr, true);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>