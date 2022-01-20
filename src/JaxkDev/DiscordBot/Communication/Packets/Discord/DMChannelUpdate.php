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
use JaxkDev\DiscordBot\Models\Channels\DMChannel;

class DMChannelUpdate extends Packet
{

    /** @var DMChannel */
    private $channel;

    /** @var DMChannel */
    private $old;

    public function __construct(DMChannel $channel, DMChannel $old)
    {
        parent::__construct();
        $this->channel = $channel;
        $this->old = $old;
    }

    public function getChannel(): DMChannel
    {
        return $this->channel;
    }
    public function getOldChannel(): DMChannel
    {
        return $this->old;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->channel,
            $this->old
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->channel,
            $this->old
        ] = unserialize($data);
    }
}
