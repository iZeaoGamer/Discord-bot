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

namespace JaxkDev\DiscordBot\Plugin\Events;

use JaxkDev\DiscordBot\Models\Role;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a role gets updated.
 * 
 * @see RoleDeleted
 * @see RoleCreated
 */
class RoleUpdated extends DiscordBotEvent
{

    /** @var Role */
    private $role;

    /** @var Role */
    private $old;

    public function __construct(Plugin $plugin, Role $role, Role $old)
    {
        parent::__construct($plugin);
        $this->role = $role;
        $this->old = $old;
    }

    public function getRole(): Role
    {
        return $this->role;
    }
    public function getOldRole(): Role{
        return $this->old;
    }
}
