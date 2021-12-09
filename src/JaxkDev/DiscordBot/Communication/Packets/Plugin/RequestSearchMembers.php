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

class RequestSearchMembers extends Packet
{

    /** @var string */
    private $server_id;

    /** @var string */
    private $user_id;

    /** @var int */
    private $limit;

    public function __construct(string $server_id, string $user_id, int $limit)
    {
        parent::__construct();
        $this->server_id = $server_id;
        $this->user_id = $user_id;
        $this->limit = $limit;
    }

    public function getServerId(): string
    {
        return $this->server_id;
    }
    public function getSearchedUserId(): string
    {
        return $this->user_id;
    }
    public function getLimit(): int
    {
        return $this->limit;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->server_id,
            $this->user_id,
            $this->limit
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->server_id,
            $this->user_id,
            $this->limit
        ] = unserialize($data);
    }
}
