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

use JaxkDev\DiscordBot\Models\User\User;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a user is updated.
 * 
 */
class UserUpdated extends DiscordBotEvent
{

    /** @var User */
    private $user;

    /** @var User|null */
    private $old;

    public function __construct(Plugin $plugin, User $user, ?User $old)
    {
        parent::__construct($plugin);
        $this->user = $user;
        $this->old = $old;
    }

    public function getUser(): User
    {
        return $this->user;
    }
    public function getOldUser(): ?User
    {
        return $this->old;
    }
}
