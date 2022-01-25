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

use JaxkDev\DiscordBot\Models\Intergration;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a Intergration gets updated.
 * 
 * @see IntergrationCreated
 * @see IntergrationDeleted
 */
class IntergrationUpdated extends DiscordBotEvent
{

    /** @var Intergration */
    private $intergration;

    public function __construct(Plugin $plugin, Intergration $intergration)
    {
        parent::__construct($plugin);
        $this->intergration = $intergration;
    }

    public function getIntergration(): Intergration
    {
        return $this->intergration;
    }
}
