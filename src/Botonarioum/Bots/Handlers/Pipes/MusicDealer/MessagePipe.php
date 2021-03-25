<?php declare(strict_types=1);

namespace App\Botonarioum\Bots\Handlers\Pipes\MusicDealer;

use App\Botonarioum\Bots\Handlers\Pipes\MessagePipe as BaseMessagePipe;
use App\Botonarioum\Bots\Handlers\Pipes\MusicDealer\Keyboards\TrackFinderSearchResponseKeyboard;
use App\Botonarioum\TrackFinder\Page;
use App\Botonarioum\TrackFinder\TrackFinderService;
use Formapro\TelegramBot\Bot;
use Formapro\TelegramBot\EditMessageText;
use Formapro\TelegramBot\SendMessage;
use Formapro\TelegramBot\Update;
use Psr\Log\LoggerInterface;
use function Formapro\Values\get_values;

class MessagePipe extends BaseMessagePipe
{
    /**
     * @var TrackFinderService
     */
    protected $trackFinderService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TrackFinderSearchResponseKeyboard
     */
    private $trackFinderSearchResponseKeyboard;

    private $client;

    public function __construct(LoggerInterface $logger, TrackFinderSearchResponseKeyboard $trackFinderSearchResponseKeyboard)
    {
        $this->logger = $logger;
        $this->trackFinderService = new TrackFinderService();
        $this->trackFinderSearchResponseKeyboard = $trackFinderSearchResponseKeyboard;
        $this->client = new \GuzzleHttp\Client();
    }

    public function processing(Bot $bot, Update $update): bool
    {
        try {
            $this->logger->error('-----1-----');
            $message = new SendMessage(
                $update->getMessage()->getChat()->getId(),
                '🔎 Ищу...'
            );

            $sendSearchMessage = $bot->sendMessage($message);
            $this->logger->error('-----2-----');

            if (empty($update->getMessage()->getText())) {
                $message = new SendMessage(
                    $update->getMessage()->getChat()->getId(),
                    'Не найдено :('
                );

                $bot->sendMessage($message);

                // todo: обновляем клавиатуру

                return true;
            }
            $this->logger->error('-----3-----');

            $searchResponse = $this->trackFinderService->search($update->getMessage()->getText(), Page::DEFAULT_LIMIT, Page::DEFAULT_OFFSET);

            if ($searchResponse->isEmpty()) {
                $message = new SendMessage(
                    $update->getMessage()->getChat()->getId(),
                    'Не найдено :('
                );

                $bot->sendMessage($message);

                // todo: обновляем клавиатуру

                return true;
            }
            $this->logger->error('-----4-----');
            var_dump('------------------------------------');

            $markup = $this->trackFinderSearchResponseKeyboard->build($searchResponse, $update);
            $this->logger->error(json_encode(get_values($markup)));

            $newMessage = EditMessageText::withChatId(
                '🎶 Результат поиска: ' . mb_convert_encoding(substr($update->getMessage()->getText(), 0, 20), 'UTF-8', 'UTF-8'),
                $update->getMessage()->getChat()->getId(),
                $sendSearchMessage->getMessageId()

            );

            $this->logger->error('-----5-----');

            $newMessage->setReplyMarkup($markup);
            $this->logger->error(json_encode(get_values($newMessage)));

            $this->logger->error('-----6-----');


            $data = [
                'reply_markup' => ['inline_keyboard' => [$markup]]
            ];

            $bot->

             $url = sprintf('https://api.telegram.org/bot%s/%s', $bot->getToken(), 'editMessageText');

            $this->client->post($url, [
                'json' => $data,
            ]);

//            $message = $bot->editMessageText($newMessage);
//            var_dump($message);

            $this->logger->error('-----7-----');
        } catch (\Throwable $exception) {
//            {"ok":false,"error_code":400,"description":"Bad Request: field \"inline_keyboard\" of the InlineKeyboardMarkup must be an Array"} [] []
            $this->logger->error('--------------------------' . PHP_EOL);
//            $this->logger->error($exception->getResponse()->getBody());
            $this->logger->error($exception->getMessage());
            $this->logger->error($exception->getFile());
            $this->logger->error($exception->getLine());
            $this->logger->error($exception->getTraceAsString());
            $this->logger->error('--------------------------' . PHP_EOL);
        }


        return true;
    }
}