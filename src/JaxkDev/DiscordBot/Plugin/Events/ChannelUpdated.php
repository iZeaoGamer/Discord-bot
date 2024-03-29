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

use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a channel gets updated.
 * 
 * @see ChannelDeleted
 * @see ChannelCreated
 */
class ChannelUpdated extends DiscordBotEvent
{

    /** @var ServerChannel */
    private $channel;

    /** @var ServerChannel */
    private $old;

    public function __construct(Plugin $plugin, ServerChannel $channel, ServerChannel $old)
    {
        parent::__construct($plugin);
        $this->channel = $channel;
        $this->old = $old;
    }

    public function getChannel(): ServerChannel
    {
        return $this->channel;
    }
    public function getOldChannel(): ServerChannel{
        return $this->old;
    }
}
