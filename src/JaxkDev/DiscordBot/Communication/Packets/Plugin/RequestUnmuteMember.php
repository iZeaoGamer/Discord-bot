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

class RequestUnmuteMember extends Packet
{

    /** @var VoiceChannel */
    private $channel;

    /** @var string */
    private $userId;

    public function __construct(string $user_id, VoiceChannel $channel)
    {
        parent::__construct();
        $this->userId = $user_id;
        $this->channel = $channel;
    }
    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getChannel(): VoiceChannel
    {
        return $this->channel;
    }


    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->userId,
            $this->channel
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->userId,
            $this->channel
        ] = unserialize($data);
    }
}
