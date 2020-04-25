<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\ProfileInfoHandler;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe as BaseMessagePipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class MessagePipe extends BaseMessagePipe
{
    public function processing(Bot $bot, Update $update): bool
    {
        $user = $update->getMessage()->getFrom();

        $userInfo = 'ID: ' . $user->getId();
        $userInfo .= 'First name: ' . (string)$user->getFirstName() . PHP_EOL;
        $userInfo .= 'Last name: ' . (string)$user->getLastName() . PHP_EOL;
        $userInfo .= 'User name: ' . (string)$user->getUsername() . PHP_EOL;
        $userInfo .= 'Language code: ' . (string)$user->getLanguageCode() . PHP_EOL;

        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            $userInfo
        ));

        return true;
    }
}