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

class RequestRemoveAllReactions extends Packet
{

    /** @var string */
    private $channel_id;

    /** @var string */
    private $message_id;

    /** @var null|string */
    private $emoji;

    /** @var string|null */
    private $thread_id;

    public function __construct(string $channel_id, string $message_id, ?string $emoji = null, ?string $thread_id = null)
    {
        parent::__construct();
        $this->channel_id = $channel_id;
        $this->message_id = $message_id;
        $this->emoji = $emoji;
        $this->thread_id = $thread_id;
    }

    public function getChannelId(): string
    {
        return $this->channel_id;
    }

    public function getMessageId(): string
    {
        return $this->message_id;
    }

    public function getEmoji(): ?string
    {
        return $this->emoji;
    }
    public function getThreadId(): ?string
    {
        return $this->thread_id;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->channel_id,
            $this->message_id,
            $this->emoji,
            $this->thread_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->channel_id,
            $this->message_id,
            $this->emoji,
            $this->thread_id
        ] = unserialize($data);
    }
}
