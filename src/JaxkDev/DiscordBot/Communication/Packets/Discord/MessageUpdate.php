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

namespace JaxkDev\DiscordBot\Communication\Packets\Discord;

use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class MessageUpdate extends Packet
{

    /** @var Message */
    private $message;

    /** @var Message|null */
    private $old;

    public function __construct(Message $message, ?Message $old)
    {
        parent::__construct();
        $this->message = $message;
        $this->old = $old;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
    public function getOldMessage(): ?Message
    {
        return $this->old;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->message,
            $this->old
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->message,
            $this->old
        ] = unserialize($data);
    }
}
