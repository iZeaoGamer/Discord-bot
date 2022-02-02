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
 * Emitted when a server the bot is in has been updated, eg Changed icon, name, region etc.
 * 
 * @see ServerDeleted Emitted when the bot leaves a server
 * @see ServerJoined Emitted when the bot joins a server.
 */
class ServerUpdated extends DiscordBotEvent
{

    /** @var Server */
    private $server;

    /** @var Server */
    private $old;

    public function __construct(Plugin $plugin, Server $server, Server $old)
    {
        parent::__construct($plugin);
        $this->server = $server;
        $this->old = $old;
    }

    public function getServer(): Server
    {
        return $this->server;
    }
    public function getOldServer(): Server{
        return $this->old;
    }
}
