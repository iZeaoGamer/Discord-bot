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

use JaxkDev\DiscordBot\Models\Emoji;
use pocketmine\plugin\Plugin;

/**
 * Emitted when an guild emoji gets updated.
 * 
 */
class ServerEmojiUpdated extends DiscordBotEvent
{

    /** @var Emoji */
    private $emoji;

    public function __construct(Plugin $plugin, Emoji $emoji)
    {
        parent::__construct($plugin);
        $this->emoji = $emoji;
    }
    public function getSticker(): Emoji
    {
        return $this->emoji;
    }
}
