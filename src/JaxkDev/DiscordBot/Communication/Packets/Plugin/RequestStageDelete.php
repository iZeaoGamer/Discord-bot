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

use JaxkDev\DiscordBot\Models\Channels\Stage;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestStageDelete extends Packet
{

    /** @var string */
    private $serverId;

    /** @var string */
    private $stageId;

    public function __construct(string $server_id, string $stage_id)
    {
        parent::__construct();
        $this->serverId = $server_id;
        $this->stageId = $stage_id;
    }

    public function getServerId(): string
    {
        return $this->serverId;
    }
    public function getStageId(): string{
        return $this->stageId;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->serverId,
            $this->stageId
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->serverId,
            $this->stageId
        ] = unserialize($data);
    }
}