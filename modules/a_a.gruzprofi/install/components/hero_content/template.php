<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<?php if (!empty($arResult["ITEMS"])): ?>
    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
        <h1><?= htmlspecialchars($arItem["NAME"]) ?></h1>
        
        <?php
        // Подзаголовок из PREVIEW_TEXT или свойства SUBTITLE
        $subtitle = "";
        if (!empty($arItem["PREVIEW_TEXT"])) {
            $subtitle = $arItem["PREVIEW_TEXT"];
        } elseif (!empty($arItem["DISPLAY_PROPERTIES"]["SUBTITLE"]["VALUE"])) {
            $subtitle = $arItem["DISPLAY_PROPERTIES"]["SUBTITLE"]["VALUE"];
        }
        ?>
        <?php if (!empty($subtitle)): ?>
            <p class="subtitle"><?= htmlspecialchars($subtitle) ?></p>
        <?php endif; ?>
        
        <?php
        // Преимущества из свойства BENEFITS
        $benefits = [];
        if (!empty($arItem["DISPLAY_PROPERTIES"]["BENEFITS"]["VALUE"])) {
            if (is_array($arItem["DISPLAY_PROPERTIES"]["BENEFITS"]["VALUE"])) {
                $benefits = $arItem["DISPLAY_PROPERTIES"]["BENEFITS"]["VALUE"];
            } else {
                $benefits = explode("\n", $arItem["DISPLAY_PROPERTIES"]["BENEFITS"]["VALUE"]);
            }
        }
        
        // Дефолтные преимущества, если нет в инфоблоке
        if (empty($benefits)) {
            $benefits = [
                "Без скрытых доплат за этажи и проносы",
                "Собственный автопарк - не срываем сроки",
                "Аккуратные штатные грузчики (граждане РФ)"
            ];
        }
        ?>
        
        <?php if (!empty($benefits)): ?>
            <ul class="benefits-list">
                <?php foreach ($benefits as $benefit): ?>
                    <?php $benefit = trim($benefit); ?>
                    <?php if (!empty($benefit)): ?>
                        <li><?= htmlspecialchars($benefit) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
    <?php endforeach; ?>
<?php else: ?>
    <!-- Дефолтный контент, если инфоблок пуст -->
    <h1>Квартирные и офисные переезды с фиксированной ценой</h1>
    <p class="subtitle">Подача чистой машины от 30 минут. Несем 100% материальную ответственность по договору за каждую царапину.</p>
    <ul class="benefits-list">
        <li>Без скрытых доплат за этажи и проносы</li>
        <li>Собственный автопарк - не срываем сроки</li>
        <li>Аккуратные штатные грузчики (граждане РФ)</li>
    </ul>
<?php endif; ?>