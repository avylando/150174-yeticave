<?php

require_once 'mysql_helper.php';

/* Рендеринг шаблона
 * @param string $template_src Путь к файлу шаблона
 * @param array $data Массив данных, используемых в шаблоне
 * @return string Сгенерированный шаблон
 */
function render_template ($template_src, $data) {
    if (file_exists($template_src)) {
        extract($data);
        ob_start();
        include $template_src;
        return ob_get_clean();
    }

    return '';
}

/* Форматирование цены
 * Проверяет и форматирует переданное число
 * @param integer $price Значение цены
 * @return string Отформатированная строка
 */
function format_price($price) {
    if (is_numeric($price)) {
        $int_price = ceil($price);
        if ($int_price >= 1000) {
            $int_price = number_format($int_price, 0, '.', ' ');
        }

        $formatted_price = $int_price . ' ';
        return $formatted_price;
    }

    return '';
}

/* Установка таймера
 * Вычисляет оставшееся время с текущего момента до переданной даты
 * @param string $date Дата истечения срока
 * @return string Отформатированная строка с остатком дней/часов/минут до указанного срока
 */
function set_timer($date) {
    if (strtotime($date)) {
        date_default_timezone_set('Europe/Moscow');
        $diff = strtotime($date) - time();
        $hours = floor($diff / 3600);
        $time_left = '';

        if ($hours > 23) {
            $days = floor($hours / 24);
            $time_left = $days . ' дн.';

        } else if ($hours > 0) {
            $minutes = floor(($diff / 60) - ($hours * 60));

            if (strlen($minutes) === 1) {
                $minutes = '0' . $minutes;
            }

            $time_left = $hours . ':' . $minutes;

        } else {
            $time_left = 'Закрыт';
        }

        return $time_left;
    }

    return 'Некорректный формат даты';
}

/* Валидация даты
 * @param string $date Дата
 * @return string Строка с ошибкой/пустая строка
 */
function check_date($date) {
    if (strtotime($date)) {
        $end_date = strtotime($date);
        $days_remain = floor(($end_date - time()) / 86400);

        if ($days_remain < 0) {
            return 'Введите корректную дату';
        }

        return '';
    }

    return 'Введите дату в формате «ДД.ММ.ГГГГ»';
}

/* Проверка формата изображения (PNG, JPEG)
 * @param string $file Путь к файлу изображения
 * @return string/boolean Строка с путем к сохраненному файлу/false
 */

function check_image_format($file) {
    $tmp_name = $file['tmp_name'];
    $path = 'img/' . $file['name'];

    $file_type = mime_content_type($tmp_name);

    if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
        return false;
    }

    move_uploaded_file($tmp_name, $path);
    return $path;
}

/* Проверка авторизации пользователя
 * @param array $session Массив сессии
 * @return array Массив с данными об авторизации
 */
function check_authorization($session) {
    $result = [];

    if (isset($session['user'])) {
        $result['is_authorized'] = true;
        $result['user'] = $session['user'];
    }

    return $result;
}

/* Получение категорий
 * @param array $connect Ресурс соединения с БД
 * @return array Массив с данными
 */
function get_categories($connect) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = 'SELECT * FROM category';
    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* Получение открытых лотов
 * @param array $connect Ресурс соединения с БД
 * @return array Массив с данными
 */
function get_active_lots($connect) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = 'SELECT lot.id, creation_date, lot.name, category.name AS category, message, photo, start_price, step, expiration_date,
    (SELECT COUNT(*) FROM bet WHERE lot.id = bet.lot_id) AS bets_number
    FROM lot INNER JOIN category ON category.id = lot.category_id
    WHERE NOW() BETWEEN creation_date AND expiration_date
    ORDER BY creation_date DESC';

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* Получение лота по ID
 * @param array $connect Ресурс соединения с БД
 * @param integer $id ID лота
 * @return array Массив с данными
 */
function get_lot_by_id($connect, $id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }
    $id = intval($id);

    $sql = 'SELECT lot.id, lot.name, category.name AS category, message, photo, start_price, step, expiration_date FROM lot
    JOIN category ON lot.category_id = category.id
    WHERE lot.id = ' . $id;

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    if (!mysqli_num_rows($result)) {
        http_response_code(404);
        return false;
    }

    return $lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
}

/* Получение ставок для лота
 * @param array $connect Ресурс соединения с БД
 * @param integer $id ID лота
 * @return array Массив с данными
 */
function get_bets_by_lot_id($connect, $id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT bet.sum, DATE_FORMAT (bet.date, '%d.%m.%y %H:%i') AS date, user.name AS user FROM bet
    JOIN lot ON bet.lot_id = lot.id
    JOIN user ON bet.user_id = user.id
    WHERE bet.lot_id = '$id'
    ORDER BY bet.date DESC, bet.sum DESC";

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);

}

/* Получение максимальной ставки для лота
 * @param array $connect Ресурс соединения с БД
 * @param integer $id ID лота
 * @return integer Значение ставки
 */
function get_max_bet_for_lot($connect, $id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT MAX(sum) AS max_bet FROM bet WHERE lot_id = " . $id;

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    $row = mysqli_fetch_assoc($result);
    $max = $row['max_bet'];

    return $max;
}

/* Получение пользователя по Email
 * @param array $connect Ресурс соединения с БД
 * @param string $login Email пользователя
 * @return array Массив с данными
 */
function get_user_by_login($connect, $login) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT * FROM user
    WHERE email = ?";

    $input_user = mysqli_real_escape_string($connect, $login);
    $stmt = db_get_prepare_stmt($connect, $sql, [$input_user]);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $current_user = mysqli_fetch_assoc($result);
}

/* Поиск Email
 * @param array $connect Ресурс соединения с БД
 * @param string $email Email пользователя
 * @return string Строку с ошибкой/null
 */
function search_email_in_db($connect, $email) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT email FROM user WHERE email = ?";

    $search_email = mysqli_real_escape_string($connect, $email);
    $stmt = db_get_prepare_stmt($connect, $sql, [$search_email]);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    if (!empty(mysqli_fetch_assoc($result))) {
        return 'Пользователь с таким адресом уже зарегистрирован';
    }

    return null;
}

/* Добавление пользователя
 * @param array $connect Ресурс соединения с БД
 * @param array $userdata Данные пользователя
 * @param string $file_path Путь к файлу аватара
 * @return boolean Результат запроса
 */
function add_user($connect, $userdata, $file_path) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "INSERT INTO user (name, email, password, contacts, avatar)
    VALUES (?, ?, ?, ?, ?)";

    $user_password = password_hash($userdata['password'], PASSWORD_DEFAULT);
    $stmt = db_get_prepare_stmt($connect, $sql, [$userdata['user_name'], $userdata['email'], $user_password, $userdata['contacts'], $file_path]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

/* Добавление лота
 * @param array $connect Ресурс соединения с БД
 * @param array $lot Данные лота
 * @param integer $user_id ID пользователя
 * @return boolean Результат запроса
 */
function add_lot($connect, $lot, $user_id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "INSERT INTO lot (name, category_id, message, photo, start_price, step, expiration_date, author_user_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = db_get_prepare_stmt($connect, $sql, [$lot['name'], $lot['category'], $lot['message'], $lot['photo'], $lot['start_price'], $lot['step'], $lot['expiration_date'], $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

/* Добавление ставки
 * @param array $connect Ресурс соединения с БД
 * @param integer $bet_sum Сумма ставки
 * @param integer $lot_id ID лота
 * @param integer $user_id ID пользователя
 * @return boolean Результат запроса
 */
function add_bet($connect, $bet_sum, $lot_id, $user_id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "INSERT INTO bet (sum, lot_id, user_id) VALUES (?, ?, ?)";

    $stmt = db_get_prepare_stmt($connect, $sql, [$bet_sum, $lot_id, $user_id]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

/* Обновление цены лота
 * @param array $connect Ресурс соединения с БД
 * @param integer $price Значение цены
 * @param integer $lot_id ID лота
 * @return boolean Результат запроса
 */
function update_price($connect, $price, $lot_id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "UPDATE lot SET start_price = ? WHERE id = " . $lot_id;

    $stmt = db_get_prepare_stmt($connect, $sql, [$price]);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

/* Подсчет количества совпадающих записей по ключевому слову
 * @param array $connect Ресурс соединения с БД
 * @param string $keyword Ключевое слово для поиска
 * @return boolean Результат запроса
 */
function count_lots_by_keyword($connect, $keyword) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT COUNT(*) AS counter FROM lot WHERE MATCH(lot.name, lot.message) AGAINST(?)";

    $stmt = db_get_prepare_stmt($connect, $sql, [$keyword]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $count = mysqli_fetch_assoc($result)['counter'];
}

/* Поиск лотов по ключевому слову
 * @param array $connect Ресурс соединения с БД
 * @param string $keyword Ключевое слово для поиска
 * @param integer $limit Лимит поиска
 * @param integer $offset Сдвиг в выборке
 * @return boolean Результат запроса
 */
function search_lots_by_keyword($connect, $keyword, $limit, $offset) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT lot.id, lot.name, category.name AS category, lot.message, lot.photo, lot.start_price, lot.step,
        lot.expiration_date, (SELECT COUNT(*) FROM bet WHERE lot.id = bet.lot_id) AS bets_number
        FROM lot INNER JOIN category ON lot.category_id = category.id WHERE MATCH(lot.name, lot.message) AGAINST(?)
        ORDER BY creation_date DESC LIMIT $limit OFFSET ". $offset;

    $stmt = db_get_prepare_stmt($connect, $sql, [$keyword]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* Подсчет количества совпадающих записей по id категории
 * @param array $connect Ресурс соединения с БД
 * @param integer $category_id ID категории
 * @return boolean Результат запроса
 */
function count_lots_by_category($connect, $category_id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $category_id = intval($category_id);
    $sql = "SELECT COUNT(*) AS counter FROM lot WHERE category_id = ?";

    $stmt = db_get_prepare_stmt($connect, $sql, [$category_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $count = mysqli_fetch_assoc($result)['counter'];
}

/* Поиск лотов по ID категории
 * @param array $connect Ресурс соединения с БД
 * @param integer $category_id ID категории
 * @param integer $limit Лимит поиска
 * @param integer $offset Сдвиг в выборке
 * @return boolean Результат запроса
 */
function search_lots_by_category($connect, $category_id, $limit, $offset) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT lot.id, lot.name, category.name AS category, lot.message, lot.photo, lot.start_price, lot.step,
        lot.expiration_date, (SELECT COUNT(*) FROM bet WHERE lot.id = bet.lot_id) AS bets_number
        FROM lot INNER JOIN category ON lot.category_id = category.id WHERE category_id = ?
        ORDER BY creation_date DESC LIMIT $limit OFFSET ". $offset;

    $stmt = db_get_prepare_stmt($connect, $sql, [$category_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* Поиск ставок пользователя
 * @param array $connect Ресурс соединения с БД
 * @param integer $user_id ID пользователя
 * @return array Массив данных
 */
function search_bets_by_user($connect, $user_id) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = "SELECT lot.id AS lot_id, lot.name AS lot_name, lot.photo AS lot_photo, lot.start_price AS lot_price,
        lot.expiration_date, lot.winner_user_id AS winner, user.contacts AS owner_contacts, bet.sum, bet.date
        FROM bet
        INNER JOIN lot ON lot.id = bet.lot_id
        INNER JOIN category ON lot.category_id = category.id
        INNER JOIN user ON lot.author_user_id = user.id
        WHERE user_id = ?
        ORDER BY creation_date DESC";

    $stmt = db_get_prepare_stmt($connect, $sql, [$user_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $bets = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* Определение победителей для истекших лотов
 * @param array $connect Ресурс соединения с БД
 * @return array Массив данных
 */
function get_winners($connect) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $sql = 'SELECT lot.name AS lot_name, lot.id AS lot_id,
            user.name AS user_name, user.email AS user_email, user.id AS user_id
            FROM bet
            INNER JOIN (
            SELECT lot_id, MAX(sum) AS max_sum
            FROM bet
            GROUP BY lot_id
            ) AS tmp ON tmp.lot_id = bet.lot_id AND tmp.max_sum = bet.sum
            INNER JOIN user ON user.id = bet.user_id
            INNER JOIN lot ON lot.id = bet.lot_id
            WHERE NOW() >= expiration_date AND winner_user_id IS NULL';

    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* Добавление ID победителей в таблицу лотов
 * @param array $connect Ресурс соединения с БД
 * @param data $user_id Массив данных
 * @return boolean Результат запроса
 */
function update_winners($connect, $data) {
    if (!$connect) {
        throw new Exception(mysqli_connect_error());
    }

    $queries = [];

    foreach ($data as $index => $item) {
        $queries[$index] = "UPDATE lot SET winner_user_id = " . $item['user_id'] . " WHERE id = " . $item['lot_id'];
    }

    $sql = implode(';', $queries);
    $result = mysqli_query($connect, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }

    return $result;
}

/* Подготовка данных для рендеринга базового шаблона
 * @param array $connect Ресурс соединения с БД
 * @param string $title Заголовок страницы
 * @param array $session Сессия
 * @param integer $content Контент для рендеринга
 * @param integer $current_category ID текущей категории (Необязательный параметр)
 * @return array Массив с данными
 */
function prepare_data_for_layout($connect, $title, $session, $content, $current_category = null) {
    try {
        $categories = get_categories($connect);

    } catch (Exception $error) {
        $title = 'Ошибка';
        $categories = [];
        $content = render_template('templates/error.php', ['error' => $error->getMessage()]);
    }

    return $data = [
        'title' => $title,
        'session' => check_authorization($session),
        'categories' => $categories,
        'content' => $content,
        'current_category' => $current_category
    ];
}
