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

class RequestMoveMember extends Packet
{

    /** @var VoiceChannel */
    private $channel;

    /** @var string */
    private $userId;

    /** @var string|null */
    private $reason;

    public function __construct(string $user_id, VoiceChannel $channel, ?string $reason = null)
    {
        parent::__construct();
        $this->userId = $user_id;
        $this->channel = $channel;
        $this->reason = $reason;
    }
    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getChannel(): VoiceChannel
    {
        return $this->channel;
    }
    public function getReason(): ?string
    {
        return $this->reason;
    }


    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->userId,
            $this->channel,
            $this->reason
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->userId,
            $this->channel,
            $this->reason
        ] = unserialize($data);
    }
}
