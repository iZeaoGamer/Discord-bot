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

class RequestCreateReaction extends Packet
{

    /** @var Reaction */
    private $reaction;

    /** @var string|null */
    private $thread_id;

    public function __construct(Reaction $reaction, ?string $thread_id)
    {
        parent::__construct();
        $this->reaction = $reaction;
        $this->thread_id = $thread_id;
    }

    public function getReaction(): Reaction
    {
        return $this->reaction;
    }
    public function getThreadId(): ?string
    {
        return $this->thread_id;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->reaction,
            $this->thread_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->reaction,
            $this->thread_id
        ] = unserialize($data);
    }
}
