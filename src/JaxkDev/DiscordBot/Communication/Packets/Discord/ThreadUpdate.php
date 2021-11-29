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
use JaxkDev\DiscordBot\Models\Channels\ThreadChannel;

class ThreadUpdate extends Packet
{

    /** @var string */
    private $member_id;

    /** @var ThreadChannel */
    private $thread;

    public function __construct(ThreadChannel $channel)
    {
        parent::__construct();
        $this->thread = $channel;
    }

    public function getChannel(): ThreadChannel
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
