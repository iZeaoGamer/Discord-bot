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

use JaxkDev\DiscordBot\Models\Interactions\Interaction;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class InteractionCreate extends Packet
{

    /** @var Interaction */
    private $interaction;

    public function __construct(Interaction $interaction)
    {
        parent::__construct();
        $this->interaction = $interaction;
    }

    public function getInteraction(): Interaction
    {
        return $this->interaction;
    }

    public function serialize(): ?string
    {
        return serialize(
            [
                $this->UID,
        $this->interaction
    ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->interaction
        ] = unserialize($data);
    }
}
