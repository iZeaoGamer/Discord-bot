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

class RequestFetchPrune extends Packet
{

    /** @var string */
    private $server_id;

    /** @var string[] */
    private $include_roles;

    /** @var int */
    private $days;



    public function __construct(string $server_id, array $include_roles, int $days = 7)
    {
        parent::__construct();
        $this->server_id = $server_id;
        $this->include_roles = $include_roles;
        $this->days = $days;
        
        
    }

    public function getServerId(): string
    {
        return $this->server_id;
    }

     /** @return string[] */
     public function getIncludedRoles(): array
     {
         return $this->include_roles;
     }
     
    public function getDays(): int
    {
        return $this->days;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->server_id,
            $this->include_roles,
            $this->days
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->server_id,
            $this->include_roles,
            $this->days
        ] = unserialize($data);
    }
}
