<div class="container">
<?php if (!empty($lots)): ?>
    <section class="lots">

      <h2>Результаты поиска по запросу «<span><?=isset($search) ? $search : ''?></span>»</h2>
      <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
        <li class="lots__item lot">
          <div class="lot__image">
            <img src="<?=$lot['photo']?>" width="350" height="260" alt="Лот">
          </div>
          <div class="lot__info">
            <span class="lot__category"><?=$lot['category']?></span>
            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['id']?>"><?=$lot['name']?></a></h3>
            <div class="lot__state">
              <div class="lot__rate">
                <span class="lot__amount"><?=intval($lot['bets_number']) !== 0 ? $lot['bets_number'] . ' ' . set_endings($lot['bets_number'], ['ставка', 'ставки', 'ставок']) : 'Стартовая цена'?></span>
                <span class="lot__cost"><?=format_price($lot['start_price'])?><b class="rub">р</b></span>
              </div>
              <div class="lot__timer timer">
                <?=set_timer($lot['expiration_date']);?>
              </div>
            </div>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
    </section>
    <?php if($pages_number > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a href="<?=($current_page > 1) ? 'search.php?search=' . $search . '&page=' . ($current_page - 1) : ''?>">Назад</a>
        </li>
        <?php foreach($pages as $page): ?>
        <li class="pagination-item <?=$page == $current_page ? 'pagination-item-active' : ''?>">
            <a href="search.php?search=<?=$search?>&page=<?=$page?>"><?=$page?></a>
        </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a href="<?=($current_page < $pages_number) ? 'search.php?search=' . $search . '&page=' . ($current_page + 1) : ''?>">Вперед</a>
        </li>
    </ul>
    <?php endif; ?>
<?php else: ?>
    <section class="lots">
        <h2>Ничего не найдено по вашему запросу</h2>
    </section>
<?php endif;?>
</div>
