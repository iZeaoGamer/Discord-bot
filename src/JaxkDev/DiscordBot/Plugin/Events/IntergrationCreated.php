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
 * Emitted when a Intergration gets created.
 * 
 * @see IntergrationUpdated
 * @see IntergrationDeleted
 */
class IntergrationCreated extends DiscordBotEvent
{

    /** @var Intergration */
    private $Intergration;

    public function __construct(Plugin $plugin, Intergration $Intergration)
    {
        parent::__construct($plugin);
        $this->Intergration = $Intergration;
    }

    public function getIntergration(): Intergration
    {
        return $this->Intergration;
    }
}
