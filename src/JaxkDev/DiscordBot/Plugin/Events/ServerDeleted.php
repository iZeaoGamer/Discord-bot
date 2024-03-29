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

use JaxkDev\DiscordBot\Models\Server\Server;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a server the bot is in has been deleted, or the bot left/was kicked.
 * 
 * @see ServerUpdated Emitted when a server the bot is in has been updated.
 * @see ServerJoined Emitted when the bot joins a server.
 */
class ServerDeleted extends DiscordBotEvent
{

    /** @var Server */
    private $server;

    public function __construct(Plugin $plugin, Server $server)
    {
        parent::__construct($plugin);
        $this->server = $server;
    }

    public function getServer(): Server
    {
        return $this->server;
    }
}
