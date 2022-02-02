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

use JaxkDev\DiscordBot\Models\Server\Role;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RoleUpdate extends Packet
{

    /** @var Role */
    private $role;

    /** @var Role */
    private $old;

    public function __construct(Role $role, Role $old)
    {
        parent::__construct();
        $this->role = $role;
        $this->old = $old;
    }

    public function getRole(): Role
    {
        return $this->role;
    }
    public function getOldRole(): Role
    {
        return $this->old;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->role,
            $this->old
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->role,
            $this->old
        ] = unserialize($data);
    }
}
