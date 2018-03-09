<?php

$win_data = get_winners($db_link);

if (!empty($win_data)) {

    $result = update_winners($db_link, $win_data);

    if ($result) {

        foreach($win_data as $winner) {
            $message_body = render_template('templates/email.php', ['data' => $winner]);

            $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
                ->setUsername('roadtoawe@mail.ru')
                ->setPassword('supermassive74');

            $mailer = new Swift_Mailer($transport);

            $message = (new Swift_Message('Ваша ставка победила'))
                ->setFrom(['roadtoawe@mail.ru' => 'Anatoly Dolgov'])
                ->setTo([strval($winner['user_email']) => strval($winner['user_name'])])
                ->setBody($message_body, 'text/html');

            $send = $mailer->send($message);
        }
    }
}
