<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $number => $category) : ?>
        <li class="nav__item <?= ($number === 0) ? "nav__item--current" : ""?>">
            <a href="all-lots.html"><?=$category['name']?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</nav>
<form class="form form--add-lot container <?=!empty($errors) ? "form--invalid" : "";?>" action="../add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?=isset($errors['name']) ? "form__item--invalid" : ""?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?=isset($lot['name']) ? $lot['name'] : "";?>">
            <span class="form__error"><?=$errors['name'];?></span>
        </div>
        <div class="form__item <?=isset($errors['category']) ? "form__item--invalid" : "";?>">
            <label for="category">Категория</label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                <option <?= ($lot['category'] === $category['name']) ? 'selected' : '';?>><?=$category['name']?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?=$errors['category'];?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?=isset($errors['message']) ? "form__item--invalid" : "";?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?=isset($lot['message']) ? $lot['message'] : "";?></textarea>
        <span class="form__error"><?=$errors['message'];?></span>
    </div>
    <div class="form__item form__item--file <?=isset($errors['photo']) ? "form__item--invalid" : "";?>">
        <label>Изображение</label>
        <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
            <img src="<?=isset($lot['photo']) ? $lot['photo'] : "img/avatar.jpg";?>" width="113" height="113" alt="Изображение лота">
        </div>
        </div>
        <div class="form__input-file">
        <input class="visually-hidden" name="photo" type="file" id="photo2" value="">
        <label for="photo2">
            <span>+ Добавить</span>
        </label>
        <span class="form__error"><?=$errors['photo'];?></span>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?=isset($errors['start_price']) ? "form__item--invalid" : "";?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="start_price" placeholder="0" value="<?=isset($lot['start_price']) ? $lot['start_price'] : "";?>">
            <span class="form__error"><?=$errors['start_price'];?></span>
        </div>
        <div class="form__item form__item--small <?=isset($errors['step']) ? "form__item--invalid" : "";?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="step" placeholder="0" value="<?=isset($lot['step']) ? $lot['step'] : "";?>">
            <span class="form__error"><?=$errors['step'];?></span>
        </div>
        <div class="form__item <?=isset($errors['expiration_date']) ? "form__item--invalid" : "";?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" name="expiration_date" value="<?=isset($lot['expiration_date']) ? $lot['expiration_date'] : "";?>">
            <span class="form__error"><?=$errors['expiration_date'];?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" name="add-lot" class="button">Добавить лот</button>
</form>
