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

use JaxkDev\DiscordBot\Models\Thread\Thread;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestThreadUnachive extends Packet
{

    /** @var Thread */
    private $thread;

    /** @var string */
    private $parent_id;

    public function __construct(Thread $thread, string $parent_id)
    {
        parent::__construct();
        $this->thread = $thread;
        $this->parent_id = $parent_id;
    }

    public function getThreadChannel(): Thread
    {
        return $this->thread;
    }

    public function getName(): string
    {
        return $this->thread->getName();
    }
    public function isPrivate(): bool
    {
        return $this->thread->isPrivate();
    }
    public function getDuration(): int
    {
        return $this->thread->getDuration();
    }
    public function getChannelId(): string
    {
        return $this->parent_id;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->thread,
            $this->parent_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->thread,
            $this->parent_id
        ] = unserialize($data);
    }
}
