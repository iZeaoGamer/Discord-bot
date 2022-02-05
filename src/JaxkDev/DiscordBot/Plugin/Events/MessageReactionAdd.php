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

use JaxkDev\DiscordBot\Models\WebSockets\MessageReaction;

/**
 * Emitted when a reaction is added to a message.
 *
 * @see MessageReactionRemove
 * @see MessageReactionRemoveAll
 * @see MessageReactionRemoveEmoji
 */
class MessageReactionAdd extends DiscordBotEvent
{

    /** @var MessageReaction */
    private $reaction;

    public function __construct(Plugin $plugin, MessageReaction $reaction)
    {
        parent::__construct($plugin);
        $this->reaction = $reaction;
    }
    public function getMessageReaction(): MessageReaction
    {
        return $this->reaction;
    }
}
