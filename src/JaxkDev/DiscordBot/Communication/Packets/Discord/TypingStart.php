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

use JaxkDev\DiscordBot\Communication\Packets\Packet;

use JaxkDev\DiscordBot\Models\WebSockets\TypingStart as TypingStartModel;

class TypingStart extends Packet
{

    /** @var TypingStartModel */
    private $typingStart;

    public function __construct(TypingStartModel $start)
    {
        parent::__construct();
        $this->typingStart = $start;
    }

    public function getTypingStart(): TypingStartModel
    {
        return $this->typingStart;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->typingStart
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->typingStart
        ] = unserialize($data);
    }
}
