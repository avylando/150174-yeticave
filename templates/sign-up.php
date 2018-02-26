<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $number => $category) : ?>
        <li class="nav__item <?= ($number === 0) ? "nav__item--current" : ""?>">
            <a href="all-lots.html"><?=$category?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</nav>

<form class="form container <?=(!empty($errors)) ? 'form--invalid' : ''?>" action="../sign-up.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?=(isset($errors['email'])) ? 'form__item--invalid' : ''?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value='<?=isset($sign_up['email']) ? $sign_up['email'] : ''?>'>
        <span class="form__error"><?=$errors['email']?></span>
    </div>
    <div class="form__item <?=(isset($errors['password'])) ? 'form__item--invalid' : ''?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль">
        <span class="form__error"><?=$errors['password']?></span>
    </div>
    <div class="form__item <?=(isset($errors['user_name'])) ? 'form__item--invalid' : ''?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="user_name" placeholder="Введите имя" value='<?=isset($sign_up['user_name']) ? $sign_up['user_name'] : ''?>'>
        <span class="form__error"><?=$errors['user_name']?></span>
    </div>
    <div class="form__item <?=(isset($errors['contacts'])) ? 'form__item--invalid' : ''?>">
        <label for="contacts">Контактные данные*</label>
        <textarea id="contacts" name="contacts" placeholder="Напишите как с вами связаться"><?=(isset($sign_up['contacts'])) ? $sign_up['contacts'] : ''?></textarea>
        <span class="form__error"><?=$errors['contacts']?></span>
    </div>
    <div class="form__item form__item--file form__item--last <?=isset($errors['avatar']) ? 'form__item--invalid' : ''?>">
        <label>Аватар</label>
        <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
            <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
        </div>
        </div>
        <div class="form__input-file">
        <input class="visually-hidden" type="file" name="avatar" id="photo2" value="">
        <label for="photo2">
            <span>+ Добавить</span>
        </label>
        </div>
        <span class="form__error"><?=$errors['avatar']?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button" name="sign-up">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
