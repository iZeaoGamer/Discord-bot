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

use JaxkDev\DiscordBot\Models\Server\ServerScheduledEvent;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a Scheduled Event has been created.
 * 
 * @see ServerScheduledCreated Emitted when a Scheduled event has been created.
 * @see ServerScheduledDeleted Emitted when a Scheduled event has been cancelled/deleted.
 * @see ServerScheduledUserAdded Emitted when a user gets added to the scheduled event.
 * @see ServerScheduledUserRemoved Emitted when a user gets removed from the scheduled event.
 */
class ServerScheduledUpdated extends DiscordBotEvent
{

    /** @var ServerScheduledEvent */
    private $schedule;

    public function __construct(Plugin $plugin, ServerScheduledEvent $schedule)
    {
        parent::__construct($plugin);
        $this->schedule = $schedule;
    }

    public function getScheduledEvent(): ServerScheduledEvent
    {
        return $this->schedule;
    }
}
