<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers;

use App\Botonarioum\Bots\Handlers\Pipes\ProfileInfoHandler\MessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\ProfileInfoHandler\StartPipe;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\Update;

class ProfileInfoHandler extends AbstractHandler
{
    private $pipes = [];

    public function __construct(StartPipe $startPipe, MessagePipe $messagePipe)
    {
        $this->pipes[] = $startPipe;
        $this->pipes[] = $messagePipe;
    }

    public function handle(Bot $bot, Update $update): bool
    {
        try {
            foreach ($this->pipes as $pipe) {
                if ($pipe->handle($bot, $update)) break;
            }
        } catch (\Throwable $exception) {
            var_dump('EXCEPTION: ' . $exception->getMessage());
        }

        return true;
    }
}