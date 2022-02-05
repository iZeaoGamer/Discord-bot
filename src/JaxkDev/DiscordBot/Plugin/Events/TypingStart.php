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

use pocketmine\plugin\Plugin;

use JaxkDev\DiscordBot\Models\WebSockets\TypingStart as TypingStartModel;

/**
 * Emitted when a user starts typing. Only works with Text channels.
 * 
 */
class TypingStart extends DiscordBotEvent
{

    /** @var TypingStartModel */
    private $typing;

    public function __construct(Plugin $plugin, TypingStartModel $typing)
    {
        parent::__construct($plugin);
        $this->typing = $typing;
    }
    public function getTypingStart(): TypingStartModel
    {
        return $this->typing;
    }
}
