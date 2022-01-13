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

class RequestTemplateDelete extends Packet
{

   /** @var string */
   private $serverId;

   /** @var string */
   private $code;

    public function __construct(string $server_id, string $code)
    {
        parent::__construct();
        $this->serverId = $server_id;
        $this->code = $code;
    }

    public function getServerId(): string
    {
        return $this->serverId;
    }
    public function getCode(): string{
        return $this->code;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->serverId,
            $this->code
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->serverId,
            $this->code
        ] = unserialize($data);
    }
}
