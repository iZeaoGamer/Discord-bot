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

class RequestUnpinMessage extends Packet
{

    /** @var string */
    private $channel_id;

    /** @var string */
    private $message_id;

    /** @var string|null */
    private $thread_id;

    /** @var string|null */
    private $reason;

    public function __construct(string $channel_id, string $message_id, ?string $thread_id = null, ?string $reason = null)
    {
        parent::__construct();
        $this->message_id = $message_id;
        $this->channel_id = $channel_id;
        $this->thread_id = $thread_id;
        $this->reason = $reason;
    }

    public function getMessageId(): string
    {
        return $this->message_id;
    }

    public function getChannelId(): string
    {
        return $this->channel_id;
    }
    public function getThreadId(): ?string
    {
        return $this->thread_id;
    }
    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->message_id,
            $this->channel_id,
            $this->thread_id,
            $this->reason
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->message_id,
            $this->channel_id,
            $this->thread_id,
            $this->reason
        ] = unserialize($data);
    }
}
