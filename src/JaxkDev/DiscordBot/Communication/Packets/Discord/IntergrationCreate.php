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

use JaxkDev\DiscordBot\Models\Server\Intergration;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class IntergrationCreate extends Packet
{

    /** @var Intergration */
    private $intergration;

    public function __construct(Intergration $intergration)
    {
        parent::__construct();
        $this->intergration = $intergration;
    }

    public function getIntergration(): Intergration
    {
        return $this->intergration;
    }

    public function serialize(): ?string
    {
        return serialize(
            [
                $this->UID,
                $this->intergration
            ]
        );
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->intergration
        ] = unserialize($data);
    }
}
