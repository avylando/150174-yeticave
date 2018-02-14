<nav class="nav">
    <ul class="nav__list container">
    <?php foreach ($categories as $number => $category) : ?>
        <li class="nav__item <?php $number === 0 ? print("nav__item--current") : ""?>">
            <a href="all-lots.html"><?=$category?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</nav>
<?php $classname = count($errors) ? "form--invalid" : "";?>
<form class="form form--add-lot container <?=$classname;?>" action="../add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $classname = isset($errors['title']) ? "form__item--invalid" : "";
          $value = isset($lot['title']) ? $lot['title'] : ""; ?>
        <div class="form__item <?=$classname;?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="title" placeholder="Введите наименование лота" value="<?=$value;?>">
            <span class="form__error"><?=$errors['title'];?></span>
        </div>
        <?php $classname = isset($errors['category']) ? "form__item--invalid" : "";?>
        <div class="form__item <?=$classname;?>">
            <label for="category">Категория</label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                <option <?php $lot['category'] === $category ? print('selected') : '';?>><?=$category?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?=$errors['category'];?></span>
        </div>
    </div>
    <?php $classname = isset($errors['message']) ? "form__item--invalid" : "";
          $value = isset($lot['message']) ? $lot['message'] : ""; ?>
    <div class="form__item form__item--wide <?=$classname;?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?=htmlspecialchars($value);?></textarea>
        <span class="form__error"><?=$errors['message'];?></span>
    </div>
    <?php $classname = isset($errors['photo']) ? "form__item--invalid" : "";
        $img_path = isset($lot['photo']) ? $lot['photo'] : "img/avatar.jpg";?>
    <div class="form__item form__item--file <?=$classname;?>">
        <label>Изображение</label>
        <div class="preview">
        <button class="preview__remove" type="button">x</button>
        <div class="preview__img">
            <img src="<?=$img_path;?>" width="113" height="113" alt="Изображение лота">
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
        <?php $classname = isset($errors['price']) ? "form__item--invalid" : "";
              $value = isset($lot['price']) ? $lot['price'] : ""; ?>
        <div class="form__item form__item--small <?=$classname;?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="price" placeholder="0" value="<?=$value;?>">
            <span class="form__error"><?=$errors['price'];?></span>
        </div>
        <?php $classname = isset($errors['step']) ? "form__item--invalid" : "";
              $value = isset($lot['step']) ? $lot['step'] : ""; ?>
        <div class="form__item form__item--small <?=$classname;?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="step" placeholder="0" value="<?=$value;?>">
            <span class="form__error"><?=$errors['step'];?></span>
        </div>
        <?php $classname = isset($errors['date']) ? "form__item--invalid" : "";
              $value = isset($lot['date']) ? $lot['date'] : ""; ?>
        <div class="form__item <?=$classname;?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" name="date" value="<?=$value;?>">
            <span class="form__error"><?=$errors['date'];?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
