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

namespace JaxkDev\DiscordBot\Communication\Packets\Plugin;

use JaxkDev\DiscordBot\Models\ServerScheduledEvent;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestScheduleCreate extends Packet
{

    /** @var ServerScheduledEvent */
    private $schedule;

    public function __construct(ServerScheduledEvent $schedule)
    {
        parent::__construct();
        $this->schedule = $schedule;
    }

    public function getScheduledEvent(): ServerScheduledEvent
    {
        return $this->schedule;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->schedule
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->schedule
        ] = unserialize($data);
    }
}
