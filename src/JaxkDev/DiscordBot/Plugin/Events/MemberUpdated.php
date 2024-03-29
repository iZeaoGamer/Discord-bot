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

use JaxkDev\DiscordBot\Models\User\Member;
use pocketmine\plugin\Plugin;

/**
 * Emitted when a member is updated, eg roles, nickname, voice status.
 * 
 * @see MemberJoined
 * @see MemberLeft
 */
class MemberUpdated extends DiscordBotEvent
{

    /** @var Member */
    private $member;

    /** @var Member|null */
    private $old;

    public function __construct(Plugin $plugin, Member $member, ?Member $old)
    {
        parent::__construct($plugin);
        $this->member = $member;
        $this->old = $old;
    }

    public function getMember(): Member
    {
        return $this->member;
    }
    public function getOldMember(): ?Member
    {
        return $this->old;
    }
}
