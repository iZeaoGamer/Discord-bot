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
use JaxkDev\DiscordBot\Models\VoiceState;

class TypingStart extends Packet
{

    /** @var string */
    private $userId;

    /** @var string */
    private $channelId;

    /** @var string|null */
    private $serverId;

    public function __construct(string $user_id, string $channel_id, ?string $server_id)
    {
        parent::__construct();
        $this->userId = $user_id;
        $this->channelId = $channel_id;
        $this->serverId = $server_id;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
    public function getChannelId(): string
    {
        return $this->channelId;
    }
    public function getServerId(): ?string
    {
        return $this->serverId;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->userId,
            $this->channelId,
            $this->serverId
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->userId,
            $this->channelId,
            $this->serverId
        ] = unserialize($data);
    }
}
