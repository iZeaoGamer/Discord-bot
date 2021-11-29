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
use JaxkDev\DiscordBot\Models\Channels\VoiceChannel;

class RequestLeaveVoiceChannel extends Packet
{

    /** @var VoiceChannel */
    private $channel;

    public function __construct(VoiceChannel $channel)
    {
        parent::__construct();
        $this->channel = $channel;
    }

    public function getChannel(): VoiceChannel
    {
        return $this->channel;
    }


    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->channel
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->channel
        ] = unserialize($data);
    }
}
