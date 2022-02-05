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

use JaxkDev\DiscordBot\Models\Server\Emoji;
use pocketmine\plugin\Plugin;

/**
 * Emitted when an guild emoji gets updated.
 * 
 */
class ServerEmojiUpdated extends DiscordBotEvent
{

    /** @var Emoji[] */
    private $newEmojis = [];

    /** @var Emoji[] */
    private $oldEmojis = [];

    public function __construct(Plugin $plugin, array $newEmojis, array $oldEmojis)
    {
        parent::__construct($plugin);
        $this->newEmojis = $newEmojis;
        $this->oldEmojis = $oldEmojis;
    }

    /** @return Emoji[] */
    public function getNewEmojis(): array
    {
        return $this->newEmojis;
    }

    /** @return Emoji[] */
    public function getOldEmojis(): array
    {
        return $this->oldEmojis;
    }
}
