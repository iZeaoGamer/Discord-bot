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

class ThreadDelete extends Packet
{


    /** @var string */
    private $channelId;

    public function __construct(string $channelId)
    {
        parent::__construct();
        $this->channelId = $channelId;
    }


    public function getChannelId(): string
    {
        return $this->channelId;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->channelId
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->channelId
        ] = unserialize($data);
    }
}
