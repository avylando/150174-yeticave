<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
    <?php foreach ($categories as $category): ?>
        <? switch ($category):
        case 'Доски и лыжи': $mod = 'boards';
            break;
        case 'Крепления': $mod = 'attachment';
            break;
        case 'Ботинки': $mod = 'boots';
            break;
        case 'Одежда': $mod = 'clothing';
            break;
        case 'Инструменты': $mod = 'tools';
            break;
        case 'Разное': $mod = 'other';
            break;
        endswitch; ?>
            <li class="promo__item promo__item--<?=$mod?>">
                <a class="promo__link" href="all-lots.html"><?=$category?></a>
            </li>
    <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?=strip_tags($lot['photo'])?>" width="350" height="260" alt="Изображение лота">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?=htmlspecialchars($lot['category'])?></span>
                <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['id']?>"><?=htmlspecialchars($lot['name'])?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?=format_price(htmlspecialchars($lot['start_price']))?><b class="rub">р</b></span>
                    </div>
                    <div class="lot__timer timer">
                        <?=set_timer($lot['expiration_date'])?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>
