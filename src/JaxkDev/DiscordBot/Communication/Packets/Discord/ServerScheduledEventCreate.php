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

namespace JaxkDev\DiscordBot\Communication\Packets\Discord;

use JaxkDev\DiscordBot\Models\Server\ServerScheduledEvent;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class ServerScheduledEventCreate extends Packet
{

    /** @var ServerScheduledEvent */
    private $event;

    public function __construct(ServerScheduledEvent $event)
    {
        parent::__construct();
        $this->event = $event;
    }

    public function getScheduledEvent(): ServerScheduledEvent
    {
        return $this->event;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->event
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->event
        ] = unserialize($data);
    }
}
