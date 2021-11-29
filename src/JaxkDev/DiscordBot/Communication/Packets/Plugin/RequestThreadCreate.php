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

use JaxkDev\DiscordBot\Models\Channels\ThreadChannel;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestThreadCreate extends Packet
{

    /** @var ThreadChannel */
    private $thread;

    public function __construct(ThreadChannel $thread)
    {
        parent::__construct();
        $this->thread = $thread;
    }

    public function getChannel(): ThreadChannel
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
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->thread
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->thread
        ] = unserialize($data);
    }
}
