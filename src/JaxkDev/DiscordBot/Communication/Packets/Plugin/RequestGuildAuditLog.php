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

class RequestGuildAuditLog extends Packet
{

    /** @var string */
    private $server_id;

    /** @var string */
    private $user_id;

    /** @var int */
    private $action_type;

    /** @var string */
    private $before;

    /** @var int */
    private $limit;

    public function __construct(string $server_id, string $user_id, int $action_type, string $before, int $limit)
    {
        parent::__construct();
        $this->server_id = $server_id;
        $this->user_id = $user_id;
        $this->action_type = $action_type;
        $this->before = $before;
        $this->limit = $limit;
    }

    public function getServerId(): string
    {
        return $this->server_id;
    }
    public function getUserId(): string
    {
        return $this->user_id;
    }
    public function getActionType(): int
    {
        return $this->action_type;
    }
    public function getBefore(): string
    {
        return $this->before;
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
            $this->action_type,
            $this->before,
            $this->limit
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->server_id,
            $this->user_id,
            $this->action_type,
            $this->before,
            $this->limit
        ] = unserialize($data);
    }
}
