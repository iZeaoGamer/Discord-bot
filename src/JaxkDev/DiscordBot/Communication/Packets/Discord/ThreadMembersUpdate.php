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

use JaxkDev\DiscordBot\Communication\Packets\Packet;
use JaxkDev\DiscordBot\Models\Thread\Thread;

class ThreadMembersUpdate extends Packet
{

    /** @var Thread */
    private $thread;

    public function __construct(Thread $channel)
    {
        parent::__construct();
        $this->thread = $channel;
    }

    public function getThread(): Thread
    {
        return $this->thread;
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
