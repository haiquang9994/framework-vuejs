<?php

namespace App\Lib\Telegram;

use Exception;
use RuntimeException;
use GuzzleHttp\Client;
use App\Lib\Telegram\Bots\TelegramBotAbstract;

class TelegramClient
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function message(string $message, TelegramBotAbstract $bot)
    {
        try {
            $botToken = $bot->botToken();
            $data = [
                'chat_id' => $bot->channelId(),
                'text' => $message
            ];
            $url = "https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query($data);
            $this->client->get($url);
        } catch (RuntimeException $e) {
        } catch (Exception $e) {
        }
    }
}
