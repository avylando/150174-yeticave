<div class="container">
<?php if (!empty($related_lots)) : ?>
    <section class="lots">
        <h2>История просмотров</h2>
        <ul class="lots__list">
            <?php foreach ($related_lots as $lot) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                <img src="<?=$lot['photo']?>" width="350" height="260" alt="Лот">
                </div>
                <div class="lot__info">
                <span class="lot__category"><?=$lot['category']?></span>
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
    <?php if($pages_number > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a href="<?=($current_page > 1) ? 'history.php?page=' . ($current_page - 1) : ''?>">Назад</a>
        </li>
        <?php foreach($pages as $page): ?>
        <li class="pagination-item <?=$page == $current_page ? 'pagination-item-active' : ''?>">
            <a href="history.php?page=<?=$page?>"><?=$page?></a>
        </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a href="<?=($current_page < $pages_number) ? 'history.php?page=' . ($current_page + 1) : ''?>">Вперед</a>
        </li>
    </ul>
    <?php endif; ?>
<?php else : ?>
    <h2>История просмотров пуста</h2>
<?php endif; ?>
</div>
