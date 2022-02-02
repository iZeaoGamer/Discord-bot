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
use JaxkDev\DiscordBot\Models\Server\Server;
use JaxkDev\DiscordBot\Communication\Packets\Packet;
use JaxkDev\DiscordBot\Models\User\User;

class ServerScheduledEventUserRemove extends Packet
{

    /** @var ServerScheduledEvent */
    private $event;

    /** @var Server */
    private $server;

    /** @var User */
    private $user;

    public function __construct(ServerScheduledEvent $event, Server $server, User $user)
    {
        parent::__construct();
        $this->event = $event;
        $this->server = $server;
        $this->user = $user;
    }

    public function getScheduledEvent(): ServerScheduledEvent
    {
        return $this->event;
    }
    public function getServer(): Server{
        return $this->server;
    }
    public function getUser(): User{
        return $this->user;
    }


    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->event,
            $this->server,
            $this->user
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->event,
            $this->server,
            $this->user
        ] = unserialize($data);
    }
}
