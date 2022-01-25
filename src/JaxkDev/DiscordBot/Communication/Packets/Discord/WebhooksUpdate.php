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

use JaxkDev\DiscordBot\Models\Server;
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class WebhooksUpdate extends Packet
{

    /** @var Server */
    private $server;

    /** @var ServerChannel|null */
    private $channel;

    public function __construct(Server $server, ?ServerChannel $channel)
    {
        parent::__construct();
        $this->server = $server;
        $this->channel = $channel;
    }

    public function getServer(): Server
    {
        return $this->server;
    }
    public function getChannel(): ?ServerChannel
    {
        return $this->channel;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->server,
            $this->channel
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->server,
            $this->channel
        ] = unserialize($data);
    }
}
