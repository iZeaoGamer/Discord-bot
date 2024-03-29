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

use JaxkDev\DiscordBot\Models\Channels\Messages\Message;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a message has been updated.
 *
 * @see MessageSent
 * @see MessageBulkDeleted
 * @see MessageDeleted
 */
class MessageUpdated extends DiscordBotEvent
{

    /** @var Message */
    private $message;

    /** @var Message|null */
    private $old;

    public function __construct(Plugin $plugin, Message $message, ?Message $old)
    {
        parent::__construct($plugin);
        $this->message = $message;
        $this->old = $old;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
    public function getOldMessage(): ?Message{
        return $this->old;
    }
}
