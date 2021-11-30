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

use JaxkDev\DiscordBot\Models\Interactions\Interaction;
use pocketmine\plugin\Plugin;

/**
 * Emitted when an interaction gets created.
 * 
 * @see InviteDeleted
 */
class InteractionCreated extends DiscordBotEvent
{

    /** @var Interaction */
    private $interaction;

    public function __construct(Plugin $plugin, Interaction $interaction)
    {
        parent::__construct($plugin);
        $this->interaction = $interaction;
    }

    public function getInteraction(): Interaction
    {
        return $this->interaction;
    }
}
