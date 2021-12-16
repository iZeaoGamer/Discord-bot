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

use JaxkDev\DiscordBot\Models\ServerScheduledEvent;
use pocketmine\plugin\Plugin;

use JaxkDev\DiscordBot\Models\Server;
use JaxkDev\DiscordBot\Models\User;

/**
 * Emitted when a Scheduled Event has been created.
 * 
 * @see ServerScheduledCreated Emitted when a Scheduled event has been created.
 * @see ServerScheduledUpdated Emitted when a Scheduled event has been updated.
 * @see ServerScheduledDeleted Emitted when a Scheduled event has been cancelled/deleted.
 * @see ServerScheduledUserAdded Emitted when a user gets added to the scheduled event.
 */
class ServerScheduledUserRemoved extends DiscordBotEvent
{

    /** @var ServerScheduledEvent */
    private $schedule;

    /** @var Server */
    private $server;

    /** @var User */
    private $user;

    public function __construct(Plugin $plugin, ServerScheduledEvent $schedule, Server $server, User $user)
    {
        parent::__construct($plugin);
        $this->schedule = $schedule;
        $this->server = $server;
        $this->user = $user;
    }

    public function getScheduledEvent(): ServerScheduledEvent
    {
        return $this->schedule;
    }
    public function getServer(): Server
    {
        return $this->server;
    }
    public function getUser(): User
    {
        return $this->user;
    }
}
