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

use JaxkDev\DiscordBot\Models\Thread\Thread;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a thread's members list gets updated,
 * 
 * @see ThreadListSynced
 * @see ThreadMemberUpdated
 */
class ThreadMembersUpdated extends DiscordBotEvent
{

    /** @var Thread */
    private $thread;

    public function __construct(Plugin $plugin, Thread $thread)
    {
        parent::__construct($plugin);
        $this->thread = $thread;
    }

    public function getThread(): Thread
    {
        return $this->thread;
    }
}
