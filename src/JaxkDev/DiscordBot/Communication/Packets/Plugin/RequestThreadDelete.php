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

use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestThreadDelete extends Packet
{

    /** @var string */
    private $channel_id;

    /** @var string */
    private $server_id;

    public function __construct(string $channel_id, string $server_id)
    {
        parent::__construct();
        $this->channel_id = $channel_id;
        $this->server_id = $server_id;
    }

    public function getChannelID(): string
    {
        return $this->channel_id;
    }
    public function getServerID(): string
    {
        return $this->server_id;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->channel_id,
            $this->server_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->channel_id,
            $this->server_id
        ] = unserialize($data);
    }
}
