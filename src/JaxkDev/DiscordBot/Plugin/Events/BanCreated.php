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

use JaxkDev\DiscordBot\Models\Server\Ban;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a ban gets created (AKA Initialised).
 * 
 * @see BanDeleted (AKA Revoked)
 */
class BanCreated extends DiscordBotEvent
{

    /** @var Ban */
    private $ban;

    public function __construct(Plugin $plugin, Ban $ban)
    {
        parent::__construct($plugin);
        $this->ban = $ban;
    }

    public function getBan(): Ban
    {
        return $this->ban;
    }
}
