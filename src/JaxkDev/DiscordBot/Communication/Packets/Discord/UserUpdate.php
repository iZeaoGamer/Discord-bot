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

use JaxkDev\DiscordBot\Models\User\User;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class UserUpdate extends Packet
{

    /** @var User */
    private $user;

    /** @var User */
    private $old;

    public function __construct(User $user, User $old)
    {
        parent::__construct();
        $this->user = $user;
        $this->old = $old;
    }

    public function getUser(): User
    {
        return $this->user;
    }
    public function getOldUser(): User
    {
        return $this->old;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->user,
            $this->old
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->user,
            $this->old
        ] = unserialize($data);
    }
}
