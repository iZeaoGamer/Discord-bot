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

namespace JaxkDev\DiscordBot\Plugin\Events;

use pocketmine\plugin\Plugin;

/**
 * Emitted when a user starts typing. Only works with Text channels.
 * 
 */
class TypingStart extends DiscordBotEvent
{

    /** @var string */
    private $userId;

    /** @var string */
    private $channelId;

    /** @var string|null */
    private $serverId;

    public function __construct(Plugin $plugin, string $userId, string $channelId, ?string $serverId)
    {
        parent::__construct($plugin);
        $this->userId = $userId;
        $this->channelId = $channelId;
        $this->serverId = $serverId;
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
}

