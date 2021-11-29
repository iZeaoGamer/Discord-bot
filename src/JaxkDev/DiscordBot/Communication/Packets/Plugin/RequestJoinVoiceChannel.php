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

class RequestJoinVoiceChannel extends Packet
{

    /** @var VoiceChannel */
    private $channel;

    /** @var bool */
    private $deaf;

    /** @var bool */
    private $muted;

    public function __construct(VoiceChannel $channel, bool $deafened, bool $muted)
    {
        parent::__construct();
        $this->channel = $channel;
        $this->deaf = $deafened;
        $this->muted = $muted;
    }

    public function getChannel(): VoiceChannel
    {
        return $this->channel;
    }
    public function isDeafend(): bool
    {
        return $this->deaf;
    }
    public function isMuted(): bool
    {
        return $this->muted;
    }


    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->channel,
            $this->deaf,
            $this->muted
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->channel,
            $this->deaf,
            $this->muted
        ] = unserialize($data);
    }
}
