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

use JaxkDev\DiscordBot\Models\Intergration;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class IntergrationUpdate extends Packet
{

    /** @var Intergration */
    private $Intergration;

    public function __construct(Intergration $Intergration)
    {
        parent::__construct();
        $this->Intergration = $Intergration;
    }

    public function getIntergration(): Intergration
    {
        return $this->Intergration;
    }

    public function serialize(): ?string
    {
        return serialize(
            [
                $this->UID,
                $this->Intergration
            ]
        );
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->Intergration
        ] = unserialize($data);
    }
}
