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

use JaxkDev\DiscordBot\Models\Channels\Messages\Reaction;

class RequestUpdateReaction extends Packet
{

    /** @var Reaction */
    private $reaction;

    public function __construct(Reaction $reaction)
    {
        parent::__construct();
        $this->reaction = $reaction;
    }

    public function getReaction(): Reaction
    {
        return $this->reaction;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->reaction
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->reaction
        ] = unserialize($data);
    }
}
