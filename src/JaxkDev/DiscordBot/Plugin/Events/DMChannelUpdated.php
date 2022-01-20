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

use JaxkDev\DiscordBot\Models\Channels\DMChannel;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a DM gets updated.
 * 
 * @see DMChannelCreated
 * @see DMChannelDeleted
 */
class DMChannelUpdated extends DiscordBotEvent
{

    /** @var DMChannel */
    private $channel;

    /** @var DMChannel */
    private $old;

    public function __construct(Plugin $plugin, DMChannel $channel, DMChannel $old)
    {
        parent::__construct($plugin);
        $this->channel = $channel;
        $this->old = $old;
    }

    public function getChannel(): DMChannel
    {
        return $this->channel;
    }
    public function getOldChannel(): DMChannel{
        return $this->old;
    }
}
