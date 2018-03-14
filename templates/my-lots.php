<section class="rates container">
<?php if($user_id === $session['user']['id']): ?>
    <?php if(!empty($bets)): ?>
        <div class="rates__head">
            <h2>Мои ставки</h2>
            <h3><a href="history.php">История просмотров</a></h3>
        </div>

        <table class="rates__list">
        <?php foreach ($bets as $bet): ?>
        <tr class="rates__item">
            <td class="rates__info">
            <div class="rates__img">
                <img src="<?=$bet['lot_photo']?>" width="54" height="40" alt="Лот">
            </div>
            <div class="rates__descr">
                <h3 class="rates__title"><a href="lot.php?id=<?=$bet['lot_id']?>"><?=$bet['lot_name']?></a></h3>
                <p><b>Контакты продавца: </b><?=$bet['winner'] === $user_id ? $bet['owner_contacts'] : '';?></p>
            </div>

            </td>
            <td class="rates__category">
                <?=$bet['lot_category']?>
            </td>
            <td class="rates__timer">
                <?php $value = set_timer($bet['expiration_date']);
                    if ($value === 'Закрыт') {
                        $finished = 'timer--finishing';
                    } else {
                        $finished = '';
                    }

                    if ($bet['winner'] === $user_id) {
                        $win = 'timer--win';
                        $value = 'Ставка выиграла';
                    } else {
                        $win = '';
                        $value = set_timer($bet['expiration_date']);
                    };?>
            <div class="timer <?=$finished?> <?=$win?>"><?=$value?></div>
            </td>
            <td class="rates__price">
                <?=$bet['sum']?> р
            </td>
            <td class="rates__time">
            <?=$bet['date']?>
            </td>
        </tr>
        <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h2>Ставки не найдены</h2>
    <?php endif; ?>
<?php else: ?>
    <h2>Доступ запрещен</h2>
<?php endif; ?>
</section>
