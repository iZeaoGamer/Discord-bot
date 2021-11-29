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

use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestThreadMessageCreate extends Packet
{

    /** @var string */
    private $message_id;

    /** @var string */
    private $channel_id;

    /** @var string */
    private $threadName;

    /** @var int */
    private $duration;

    public function __construct(string $message_id, string $channel_id, string $name, int $duration)
    {
        parent::__construct();
        $this->message_id = $message_id;
        $this->channel_id = $channel_id;
        $this->threadName = $name;
        $this->duration = $duration;
    }

    public function getMessageId(): string
    {
        return $this->message_id;
    }

    public function getChannelId(): string
    {
        return $this->channel_id;
    }
    public function getName(): string
    {
        return $this->threadName;
    }
    public function getDuration(): int
    {
        return $this->duration;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->message_id,
            $this->channel_id,
            $this->threadName,
            $this->duration
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->message_id,
            $this->channel_id,
            $this->threadName,
            $this->duration
        ] = unserialize($data);
    }
}
