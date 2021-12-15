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

use JaxkDev\DiscordBot\Models\Emoji;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestEmojiUpdate extends Packet
{

    /** @var Emoji */
    private $emoji;

    public function __construct(Emoji $emoji)
    {
        parent::__construct();
        $this->stage = $emoji;
    }

    public function getEmoji(): Emoji
    {
        return $this->emoji;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->emoji
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->emoji
        ] = unserialize($data);
    }
}
