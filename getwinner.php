<?php

$win_data = get_winners($db_link);

if (!empty($win_data)) {

    $result = update_winners($db_link, $win_data);

    if ($result) {
        foreach($win_data as $winner) {
            $message_body = render_template('templates/email.php', ['data' => $winner]);

            // Create the Transport
            $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
            ->setUsername('doingsdone@mail.ru')
            ->setPassword('rds7BgcL')
            ;

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $message = (new Swift_Message('Wonderful Subject'))
            ->setSubject('Ваша ставка победила')
            ->setFrom(['doingsdone@mail.ru' => 'Anatoly Dolgov'])
            ->setTo([strval($winner['user_email']) => strval($winner['user_name'])])
            ->setBody($message_body, 'text/html')
            ;

            // Send the message
            $send = $mailer->send($message);
        }

    }
}
