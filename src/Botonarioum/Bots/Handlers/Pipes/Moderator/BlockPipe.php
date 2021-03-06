<?php

declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\Moderator;

use App\Botonarioum\Bots\Handlers\Pipes\CommandPipe;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\MessageDTO;
use App\Botonarioum\Bots\Handlers\Pipes\Moderator\DTO\ReplyToMessageDTO;
use App\Botonarioum\Bots\Helpers\IsChatAdministrator;
use App\Entity\ModeratorBlock;
use App\Entity\ModeratorMember;
use App\Repository\ModeratorBlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;

class BlockPipe extends CommandPipe
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isSupported(Update $update): bool
    {
        if (!parent::isSupported($update)) return false;

        $message = new MessageDTO($update->getMessage());

        if (!$message->getReplyToMessage() instanceof ReplyToMessageDTO) return false;

        $command = explode(' ', $update->getMessage()->getText())[1];

        return ModeratorBlockRepository::BAN_STRATEGY_LOCAL === $command;
    }

    public function processing(Bot $bot, Update $update): bool
    {
        $isAdmin = (new IsChatAdministrator($bot, $update->getMessage()->getChat()))->checkUser($update->getMessage()->getFrom());

        if (!$isAdmin) return true;

        $this->doBlock($update, $bot);

        return true;
    }

    private function doBlock(Update $update, Bot $bot): void
    {
        $message = new MessageDTO($update->getMessage());

        $this->em->getRepository(ModeratorBlock::class)
            ->doBlocLocal(
                $message->getReplyToMessage()->getFrom()->getId(),
                $update->getMessage()->getFrom()->getId(),
                $update->getMessage()->getChat()->getId());

        $options = ['member_id' => $message->getReplyToMessage()->getFrom()->getId()];
//        $defaults = [
//            'member_id'         => $update->getMessage()->getFrom()->getId(),
//            'member_first_name' => $update->getMessage()->getFrom()->getFirstName() ?? '',
//            'member_username'   => $update->getMessage()->getFrom()->getUsername() ?? '',
//            'member_is_bot'     => $update->getMessage()->getFrom()->isBot(),
//        ];
        $defaults = [
            'member_id'         => $message->getReplyToMessage()->getFrom()->getId() ?? '',
            'member_first_name' => $message->getReplyToMessage()->getFrom()->getFirstName() ?? '',
            'member_username'   => $message->getReplyToMessage()->getFrom()->getUsername() ?? '',
            'member_is_bot'     => false
        ];

        $this->em->getRepository(ModeratorMember::class)->getOrCreate($options, $defaults);

        $bot->sendMessage(new SendMessage(
            $update->getMessage()->getChat()->getId(),
            'Пользователь ' . $message->getReplyToMessage()->getFrom()->getFirstName() . ' получает бан.'
        ));
    }
}