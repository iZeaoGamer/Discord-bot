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
use JaxkDev\DiscordBot\Models\Server\Emoji;

class ServerEmojiUpdate extends Packet
{

    /** @var Emoji[] */
    private $newEmojis = [];

    /** @var Emoji[] */
    private $oldEmojis = [];

    /** 
     * @param Emoji[] $newEmojis
     * @param Emoji[] $oldEmojis
     */
    public function __construct(array $newEmojis, array $oldEmojis)
    {
        parent::__construct();
        $this->newEmojis = $newEmojis;
        $this->oldEmojis = $oldEmojis;
    }

    /** @return Emoji[] */
    public function getNewEmojis(): array
    {
        return $this->newEmojis;
    }

    /** @return Emoji[] */
    public function getOldEmojis(): array{
        return $this->oldEmojis;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->newEmojis,
            $this->oldEmojis
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->newEmojis,
            $this->oldEmojis
        ] = unserialize($data);
    }
}
