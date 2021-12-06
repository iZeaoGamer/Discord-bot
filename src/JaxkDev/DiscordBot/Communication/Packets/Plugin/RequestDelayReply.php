<?php
/*
 * DiscordBot, PocketMine-MP Plugin.
 *
 * Licensed under the Open Software License version 3.0 (OSL-3.0)
 * Copyright (C) 2020-2021 JaxkDev
 *
 * Twitter :: @JaxkDev
 * Discord :: JaxkDev#2698
 * Email   :: JaxkDev@gmail.com
 */

namespace JaxkDev\DiscordBot\Communication\Packets\Plugin;

use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestDelayReply extends Packet
{

    /** @var Message */
    private $message;

    /** @var int */
    private $delay;

    public function __construct(Message $message, int $delay)
    {
        parent::__construct();
        $this->message = $message;
        $this->delay = $delay;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
    public function getDelay(): int
    {
        return $this->delay;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->message,
            $this->delay
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->message,
            $this->delay
        ] = unserialize($data);
    }
}
