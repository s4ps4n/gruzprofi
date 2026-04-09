<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Функция для безопасного получения строки из свойства
function getPropertyString($property, $default = '') {
    if (empty($property)) {
        return $default;
    }
    
    $value = $property;
    
    // Если пришел массив с ключом VALUE
    if (is_array($value) && isset($value['VALUE'])) {
        $value = $value['VALUE'];
    }
    
    // Если массив значений
    if (is_array($value)) {
        // Берем первый непустой элемент
        foreach ($value as $v) {
            if (!empty($v) && is_string($v)) {
                return trim($v);
            }
        }
        return $default;
    }
    
    // Если строка
    if (is_string($value)) {
        return trim($value);
    }
    
    return $default;
}

// Функция для безопасного получения массива преимуществ
function getBenefitsArray($property) {
    $result = [];
    
    if (empty($property)) {
        return $result;
    }
    
    $value = $property;
    
    // Если пришел массив с ключом VALUE
    if (is_array($value) && isset($value['VALUE'])) {
        $value = $value['VALUE'];
    }
    
    // Если массив значений
    if (is_array($value)) {
        foreach ($value as $v) {
            if (!empty($v) && is_string($v)) {
                $result[] = trim($v);
            }
        }
    } 
    // Если строка с разделителями
    elseif (is_string($value) && strpos($value, "\n") !== false) {
        $lines = explode("\n", $value);
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $result[] = $line;
            }
        }
    }
    // Если простая строка
    elseif (is_string($value) && !empty($value)) {
        $result[] = trim($value);
    }
    
    return $result;
}
?>

<?php if (!empty($arResult["ITEMS"])): ?>
    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
        <?php
        // Безопасно получаем все данные
        $title = !empty($arItem["NAME"]) ? htmlspecialchars($arItem["NAME"]) : "Надежный партнер для бизнеса";
        
        // Подзаголовок
        $subtitle = "";
        if (!empty($arItem["PROPERTIES"]["SUBTITLE"])) {
            $subtitle = getPropertyString($arItem["PROPERTIES"]["SUBTITLE"]);
        } elseif (!empty($arItem["DISPLAY_PROPERTIES"]["SUBTITLE"])) {
            $subtitle = getPropertyString($arItem["DISPLAY_PROPERTIES"]["SUBTITLE"]);
        } elseif (!empty($arItem["PREVIEW_TEXT"])) {
            $subtitle = htmlspecialchars($arItem["PREVIEW_TEXT"]);
        }
        
        // Преимущества
        $benefits = [];
        if (!empty($arItem["PROPERTIES"]["BENEFITS"])) {
            $benefits = getBenefitsArray($arItem["PROPERTIES"]["BENEFITS"]);
        } elseif (!empty($arItem["DISPLAY_PROPERTIES"]["BENEFITS"])) {
            $benefits = getBenefitsArray($arItem["DISPLAY_PROPERTIES"]["BENEFITS"]);
        }
        
        // Ссылка Telegram
        $tgLink = "";
        if (!empty($arItem["PROPERTIES"]["TG_LINK"])) {
            $tgLink = getPropertyString($arItem["PROPERTIES"]["TG_LINK"], "https://t.me/username");
        } elseif (!empty($arItem["DISPLAY_PROPERTIES"]["TG_LINK"])) {
            $tgLink = getPropertyString($arItem["DISPLAY_PROPERTIES"]["TG_LINK"], "https://t.me/username");
        } else {
            $tgLink = "https://t.me/username";
        }
        
        // Текст кнопки
        $btnText = "";
        if (!empty($arItem["PROPERTIES"]["BTN_TEXT"])) {
            $btnText = getPropertyString($arItem["PROPERTIES"]["BTN_TEXT"], "Получить прайс для юрлиц");
        } elseif (!empty($arItem["DISPLAY_PROPERTIES"]["BTN_TEXT"])) {
            $btnText = getPropertyString($arItem["DISPLAY_PROPERTIES"]["BTN_TEXT"], "Получить прайс для юрлиц");
        } else {
            $btnText = "Получить прайс для юрлиц";
        }
        
        // Дефолтные преимущества, если ничего не найдено
        if (empty($benefits)) {
            $benefits = [
                "Электронный документооборот (ЭДО)",
                "Безнал с НДС 20% / без НДС",
                "Персональный менеджер"
            ];
        }
        
        // Дефолтный подзаголовок
        if (empty($subtitle)) {
            $subtitle = "Организуем офисные переезды под ключ, доставляем товары селлеров на склады маркетплейсов (Wildberries, Ozon), развозим стройматериалы.";
        }
        ?>
        
        <div class="b2b-banner">
            <div class="b2b-content">
                <h2><?= $title ?></h2>
                <p><?= htmlspecialchars($subtitle) ?></p>
                
                <?php if (!empty($benefits)): ?>
                    <div class="b2b-tags">
                        <?php foreach ($benefits as $benefit): ?>
                            <div class="b2b-tag"><?= htmlspecialchars($benefit) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div>
                <a href="<?= htmlspecialchars($tgLink) ?>" target="_blank" rel="noopener" class="btn btn-primary">
                    <?= htmlspecialchars($btnText) ?>
                </a>
            </div>
        </div>
        
    <?php endforeach; ?>
<?php else: ?>
    <!-- Дефолтный баннер, если инфоблок пуст -->
    <div class="b2b-banner">
        <div class="b2b-content">
            <h2>Надежный партнер для бизнеса</h2>
            <p>Организуем офисные переезды под ключ, доставляем товары селлеров на склады маркетплейсов (Wildberries, Ozon), развозим стройматериалы.</p>
            <div class="b2b-tags">
                <div class="b2b-tag">Электронный документооборот (ЭДО)</div>
                <div class="b2b-tag">Безнал с НДС 20% / без НДС</div>
                <div class="b2b-tag">Персональный менеджер</div>
            </div>
        </div>
        <div>
            <a href="https://t.me/username" target="_blank" rel="noopener" class="btn btn-primary">Получить прайс для юрлиц</a>
        </div>
    </div>
<?php endif; ?>