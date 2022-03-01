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
 * Emitted when a Integration gets updated.
 * 
 * @see IntegrationCreated
 * @see IntegrationDeleted
 */
class IntegrationUpdated extends DiscordBotEvent
{

    /** @var Integration */
    private $integration;

    public function __construct(Plugin $plugin, Integration $integration)
    {
        parent::__construct($plugin);
        $this->integration = $integration;
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }
}
