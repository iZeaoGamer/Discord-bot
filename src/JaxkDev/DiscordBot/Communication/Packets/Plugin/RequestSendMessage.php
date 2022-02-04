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

use JaxkDev\DiscordBot\Models\Channels\Messages\Message;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestSendMessage extends Packet
{

    /** @var Message */
    private $message;

    /** @var string|null */
    private $thread_id;

    public function __construct(Message $message, ?string $thread_id)
    {
        parent::__construct();
        $this->message = $message;
        $this->thread_id = $thread_id;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
    public function getThreadId(): ?string
    {
        return $this->thread_id;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->message,
            $this->thread_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->message,
            $this->thread_id
        ] = unserialize($data);
    }
}
