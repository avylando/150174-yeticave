<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $number => $category) : ?>
        <li class="nav__item <?= ($number === 0) ? "nav__item--current" : ""?>">
            <a href="all-lots.html"><?=$category?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</nav>
<?php $classname = !empty($errors) ? 'form--invalid' : '';?>
<form class="form container <?=$classname?>" action="login.php" method="post">
    <h2>Вход</h2>
    <?php $classname = isset($errors['email']) ? 'form__item--invalid' : '';
          $value = !empty($login) && isset($login['email']) ? $login['email'] : '';?>
    <div class="form__item <?=$classname?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=$value?>">
        <span class="form__error"><?=$errors['email']?></span>
    </div>
    <?php $classname = isset($errors['password']) ? 'form__item--invalid' : '';?>
    <div class="form__item form__item--last <?=$classname?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
        <span class="form__error"><?=$errors['password']?></span>
    </div>
    <button type="submit" name="login" class="button">Войти</button>
</form>
