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

class RequestDelayDelete extends Packet
{

    /** @var string */
    private $message_id;

    /** @var string */
    private $channel_id;


    /** @var int */
    private $delay;

    public function __construct(string $message_id, string $channel_id, int $delay)
    {
        parent::__construct();
        $this->message_id = $message_id;
        $this->channel_id = $channel_id;
        $this->delay = $delay;
    }

    public function getMessageId(): string
    {
        return $this->message_id;
    }
    public function getChannelId(): string
    {
        return $this->channel_id;
    }
    public function getDelay(): int
    {
        return $this->delay;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->message_id,
            $this->channel_id,
            $this->delay
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->message_id,
            $this->channel_id,
            $this->delay
        ] = unserialize($data);
    }
}
