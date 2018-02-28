<section class="lot-item container">
    <?php if(isset($lot)): ?>
    <h2><?=htmlspecialchars($lot['name']);?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
        <div class="lot-item__image">
            <img src="<?=strip_tags($lot['photo']);?>" width="730" height="548" alt="Изображение лота">
        </div>
        <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['category']);?></span></p>
        <p class="lot-item__description"><?=htmlspecialchars($lot['message']);?></p>
        </div>
        <div class="lot-item__right">
        <?php if ($session['is_authorized']): ?>
        <div class="lot-item__state">
            <div class="lot-item__timer timer"><?=set_timer($lot['expiration_date'])?></div>
            <div class="lot-item__cost-state">
            <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=format_price(htmlspecialchars($lot['start_price']))?><b class="rub">р</b></span>
            </div>
            <div class="lot-item__min-cost">
                Мин. ставка <span><?=htmlspecialchars($lot['step'])?> р</span>
            </div>
            </div>
            <form class="lot-item__form" action="bet.php?lot_id=<?=$lot['id']?>" method="post">
            <p class="lot-item__form-item">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="cost" placeholder="12 000">
            </p>
            <button type="submit" class="button" name="bet">Сделать ставку</button>
            </form>
        </div>
        <?php endif; ?>
        <div class="history">
            <h3>История ставок (<span><?=count($bets)?></span>)</h3>
            <?php if(!empty($bets)): ?>
            <table class="history__list">
                <?php foreach($bets as $bet): ?>
                <tr class="history__item">
                    <td class="history__name"><?=$bet['user']?></td>
                    <td class="history__price"><?=format_price($bet['sum'])?> р</td>
                    <td class="history__time"><?=$bet['date']?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
        </div>
    </div>
    <?php else: ?>
        <h1>Запрошенный лот отсутствует</h1>
    <?php endif;?>
</section>
