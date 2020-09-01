<?php

namespace App\Lib\Telegram\Bots;

abstract class TelegramBotAbstract
{
    protected $botToken;
    protected $channelId;

    public function botToken()
    {
        return $this->botToken;
    }

    public function channelId()
    {
        return $this->channelId;
    }
}
