<form class="form container <?=!empty($errors) ? 'form--invalid' : ''?>" action="login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?=isset($errors['email']) ? 'form__item--invalid' : '';?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=!empty($login) && isset($login['email']) ? $login['email'] : '';?>">
        <span class="form__error"><?=$errors['email']?></span>
    </div>
    <div class="form__item form__item--last <?=isset($errors['password']) ? 'form__item--invalid' : '';?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
        <span class="form__error"><?=$errors['password']?></span>
    </div>
    <button type="submit" name="login" class="button">Войти</button>
</form>
