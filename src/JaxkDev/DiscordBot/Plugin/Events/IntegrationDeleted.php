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

use JaxkDev\DiscordBot\Models\Server\Integration;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a Integration gets deleted.
 * 
 * @see IntegrationCreated
 * @see IntegrationUpdated
 */
class IntegrationDeleted extends DiscordBotEvent
{

    /** @var Integration|null */
    private $integration;

    public function __construct(Plugin $plugin, ?Integration $integration)
    {
        parent::__construct($plugin);
        $this->integration = $integration;
    }

    public function getOldIntegration(): ?Integration
    {
        return $this->integration;
    }
}
