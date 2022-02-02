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

use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Server\Server;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a user is updated.
 * 
 */
class WebhooksUpdated extends DiscordBotEvent
{

    /** @var Server */
    private $server;

    /** @var ServerChannel|null */
    private $channel;

    public function __construct(Plugin $plugin, Server $server, ?ServerChannel $channel)
    {
        parent::__construct($plugin);
        $this->server = $server;
        $this->channel = $channel;
    }

    public function getServer(): Server
    {
        return $this->server;
    }
    public function getOldChannel(): ?ServerChannel
    {
        return $this->channel;
    }
}
