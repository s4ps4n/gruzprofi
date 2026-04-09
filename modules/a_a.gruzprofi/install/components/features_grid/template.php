<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (empty($arResult["ITEMS"])) return;
?>
<div class="features-grid">
<?php foreach ($arResult["ITEMS"] as $arItem):
    $icon = $arItem["PROPERTIES"]["ICON_EMOJI"]["VALUE"] ?: "✅";
?>
    <div class="feature-card">
        <div class="feature-icon"><?= htmlspecialchars($icon) ?></div>
        <h3><?= htmlspecialchars($arItem["NAME"]) ?></h3>
        <p><?= htmlspecialchars($arItem["PREVIEW_TEXT"]) ?></p>
    </div>
<?php endforeach; ?>
</div>
