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

class RequestModifyWidget extends Packet
{

    /** @var string */
    private $server_id;

    /** @var bool */
    private $enabled;

    /** @var string|null */
    private $channel_id;

    /** @var string|null */
    private $reason;


    public function __construct(string $server_id, bool $enabled, ?string $channel_id, ?string $reason = null)
    {
        parent::__construct();
        $this->server_id = $server_id;
        $this->enabled = $enabled;
        $this->channel_id = $channel_id;
        $this->reason = $reason;
    }

    public function getServerId(): string
    {
        return $this->server_id;
    }
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
    public function getChannelId(): ?string
    {
        return $this->channel_id;
    }
    public function getReason(): ?string
    {
        return $this->reason;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->server_id,
            $this->enabled,
            $this->channel_id,
            $this->reason
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->server_id,
            $this->enabled,
            $this->channel_id,
            $this->reason
        ] = unserialize($data);
    }
}
