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

use JaxkDev\DiscordBot\Models\Server\Integration;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class IntegrationUpdate extends Packet
{

    /** @var Integration */
    private $integration;

    public function __construct(Integration $integration)
    {
        parent::__construct();
        $this->integration = $integration;
    }

    public function getIntegration(): Integration
    {
        return $this->integration;
    }

    public function serialize(): ?string
    {
        return serialize(
            [
                $this->UID,
                $this->integration
            ]
        );
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->integration
        ] = unserialize($data);
    }
}
