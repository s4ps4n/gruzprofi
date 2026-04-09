<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Грузоперевозки и переезды с фиксированной ценой");

if (!CModule::IncludeModule("iblock")) {
    echo "Модуль инфоблоков не установлен!";
}
?>
<script>
window.CARS_ONLINE = [
    {"coords": [55.76, 37.64], "title": "Грузовик ГАЗель"},
    {"coords": [55.75, 37.62], "title": "Фургон Mercedes"},
    {"coords": [55.78, 37.65], "title": "Грузовик Hyundai"}
];
</script>

<!-- Первый экран -->
<section class="hero">
    <div class="container hero__grid">
        <div class="hero__content">
            <h1>Квартирные и офисные переезды с фиксированной ценой</h1>
            <p class="subtitle">Подача чистой машины от 30 минут. Несем 100% материальную ответственность по договору за каждую царапину.</p>
            <ul class="benefits-list">
                <li>Без скрытых доплат за этажи и проносы</li>
                <li>Собственный автопарк - не срываем сроки</li>
                <li>Аккуратные штатные грузчики (граждане РФ)</li>
            </ul>
        </div>
        
        <div class="calc-card" id="calc">
            <div class="calc-header">Рассчитайте стоимость переезда</div>
            <div class="form-group">
                <label class="form-label" for="calc-type">Тип переезда</label>
                <select class="form-select" id="calc-type" onchange="updateCalc()">
                    <option value="1500">Мини-переезд — 1 500 ₽</option>
                    <option value="3500">1-комнатная квартира — 3 500 ₽</option>
                    <option value="5500">2-комнатная квартира — 5 500 ₽</option>
                    <option value="8500">Офисный переезд — 8 500 ₽</option>
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
                <div style="font-size:0.9rem;color:#666;">Итого к оплате:</div>
                <div class="price-val" id="calc-total">1 500 ₽</div>
            </div>
            <button class="btn btn-primary calc-btn" onclick="alert('Позвоните нам: +7 (999) 123-45-67')">Забронировать по этой цене</button>
            <p style="font-size:0.8rem;color:#888;text-align:center;margin-top:12px;">Цена окончательная и фиксируется в договоре</p>
        </div>
    </div>
</section>

<script>
function updateCalc() {
    var typeEl = document.getElementById('calc-type');
    var moversEl = document.getElementById('calc-movers');
    if (!typeEl || !moversEl) return;
    
    var base = parseInt(typeEl.value) || 0;
    var movers = parseInt(moversEl.value) || 0;
    var total = base + movers;
    
    var el = document.getElementById('calc-total');
    if (el) el.innerText = total.toLocaleString('ru-RU') + ' ₽';
}
</script>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>