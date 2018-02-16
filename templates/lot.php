<?php session_start(); ?>

<section class="lot-item container">
    <?php if(isset($lot)): ?>
    <h2><?=htmlspecialchars($lot['title']);?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
        <div class="lot-item__image">
            <?php if (isset($lot['photo'])): ?>
            <img src="<?=strip_tags($lot['photo']);?>" width="730" height="548" alt="Изображение лота">
            <?php endif; ?>
        </div>
        <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['category']);?></span></p>
        <p class="lot-item__description"><?=htmlspecialchars($lot['message']);?></p>
        </div>
        <div class="lot-item__right">
        <?php if (!empty($_SESSION) && isset($_SESSION['user'])): ?>
        <div class="lot-item__state">
            <div class="lot-item__timer timer"><?=set_timer()?></div>
            <div class="lot-item__cost-state">
            <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=format_price(htmlspecialchars($lot['price']))?><b class="rub">р</b></span>
            </div>
            <div class="lot-item__min-cost">
                Мин. ставка <span>12 000 р</span>
            </div>
            </div>
            <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
            <p class="lot-item__form-item">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="cost" placeholder="12 000">
            </p>
            <button type="submit" class="button">Сделать ставку</button>
            </form>
        </div>
        <?php endif; ?>
        <div class="history">
            <h3>История ставок (<span>10</span>)</h3>
            <table class="history__list">
            <tr class="history__item">
                <td class="history__name">Иван</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">5 минут назад</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Константин</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">20 минут назад</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Евгений</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">Час назад</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Игорь</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 08:21</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
            </tr>
            <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
            </tr>
            </table>
        </div>
        </div>
    </div>
    <?php else: ?>
        <h1>Запрошенный лот отсутствует</h1>
    <?php endif;?>
</section>
