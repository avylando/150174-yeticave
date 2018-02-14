<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $number => $category) : ?>
        <li class="nav__item <?php $number === 0 ? print("nav__item--current") : ""?>">
            <a href="all-lots.html"><?=$category?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <?php if (isset($related_lots)) : ?>
    <section class="lots">
        <h2>История просмотров</h2>
        <ul class="lots__list">
            <?php foreach ($related_lots as $id => $lot) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                <img src="<?=$lot['photo']?>" width="350" height="260" alt="<?=$lot['alt']?>">
                </div>
                <div class="lot__info">
                <span class="lot__category"><?=$lot['category']?></span>
                <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$id?>"><?=htmlspecialchars($lot['title'])?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?=format_price(htmlspecialchars($lot['price']))?><b class="rub">р</b></span>
                    </div>
                    <div class="lot__timer timer">
                        <?=set_timer()?>
                    </div>
                </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
        <li class="pagination-item pagination-item-active"><a>1</a></li>
        <li class="pagination-item"><a href="#">2</a></li>
        <li class="pagination-item"><a href="#">3</a></li>
        <li class="pagination-item"><a href="#">4</a></li>
        <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
    </ul>
    <?php else : ?>
    <h2>История просмотров пуста</h2>
    <?php endif; ?>
</div>
