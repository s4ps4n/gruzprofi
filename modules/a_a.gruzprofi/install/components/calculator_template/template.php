<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<div class="calc-card" id="calc">
    <div class="calc-header">
        Рассчитайте стоимость переезда
    </div>
    
    <div class="form-group">
        <label class="form-label" for="calc-type">Тип переезда</label>
        <select class="form-select" id="calc-type" onchange="updateCalc()">
            <?php
            $hasTariffs = false;
            foreach ($arResult["ITEMS"] as $arItem):
                $price = (int)$arItem["PROPERTIES"]["PRICE"]["VALUE"];
                $type = $arItem["PROPERTIES"]["TARIFF_TYPE"]["VALUE"];
                if ($price > 0 && $type !== "B2B"):
                    $hasTariffs = true;
            ?>
                <option value="<?= $price ?>">
                    <?= htmlspecialchars($arItem["NAME"]) ?> — <?= number_format($price, 0, '', ' ') ?> ₽
                </option>
            <?php 
                endif;
            endforeach;
            
            // Дефолтные тарифы, если в инфоблоке нет данных
            if (!$hasTariffs):
            ?>
                <option value="1500">Мини-переезд (пара коробок / диван) — 1 500 ₽</option>
                <option value="3500">1-комнатная квартира — 3 500 ₽</option>
                <option value="5500">2-комнатная квартира — 5 500 ₽</option>
                <option value="8500">Офисный переезд (до 10 раб. мест) — 8 500 ₽</option>
            <?php endif; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label" for="calc-movers">Грузчики</label>
        <select class="form-select" id="calc-movers" onchange="updateCalc()">
            <option value="0">Справлюсь сам (только машина)</option>
            <option value="1000">1 грузчик (+1 000 ₽)</option>
            <option value="2000">2 грузчика (+2 000 ₽)</option>
            <option value="3000">3 грузчика (+3 000 ₽)</option>
        </select>
    </div>
    
    <div class="calc-result">
        <div>
            <div style="font-size:0.9rem;color:#666;">Итого к оплате:</div>
            <div class="price-val" id="calc-total">
                <?php
                // Находим первую цену для отображения
                $firstPrice = 1500;
                foreach ($arResult["ITEMS"] as $arItem) {
                    $price = (int)$arItem["PROPERTIES"]["PRICE"]["VALUE"];
                    $type = $arItem["PROPERTIES"]["TARIFF_TYPE"]["VALUE"];
                    if ($price > 0 && $type !== "B2B") {
                        $firstPrice = $price;
                        break;
                    }
                }
                echo number_format($firstPrice, 0, '', ' ') . " ₽";
                ?>
            </div>
        </div>
    </div>
    
    <button class="btn btn-primary calc-btn">Забронировать по этой цене</button>
    <p style="font-size:0.8rem;color:#888;text-align:center;margin-top:12px;">
        Цена окончательная и фиксируется в договоре
    </p>
</div>

<script>
function updateCalc() {
    var typeSelect = document.getElementById('calc-type');
    var moversSelect = document.getElementById('calc-movers');
    var totalSpan = document.getElementById('calc-total');
    
    if (!typeSelect || !moversSelect || !totalSpan) return;
    
    var basePrice = parseInt(typeSelect.value) || 0;
    var moversPrice = parseInt(moversSelect.value) || 0;
    var total = basePrice + moversPrice;
    
    totalSpan.innerText = total.toLocaleString('ru-RU') + ' ₽';
}

// Запускаем при загрузке
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateCalc);
} else {
    updateCalc();
}

// Слушаем изменения в селектах
document.addEventListener('change', function(e) {
    if (e.target.id === 'calc-type' || e.target.id === 'calc-movers') {
        updateCalc();
    }
});
</script>