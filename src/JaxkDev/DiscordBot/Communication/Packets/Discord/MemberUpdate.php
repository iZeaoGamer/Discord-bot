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

use JaxkDev\DiscordBot\Models\User\Member;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class MemberUpdate extends Packet
{

    /** @var Member */
    private $member;

    /** @var Member|null */
    private $old;

    public function __construct(Member $member, ?Member $old)
    {
        parent::__construct();
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

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->member,
            $this->old
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->member,
            $this->old
        ] = unserialize($data);
    }
}
