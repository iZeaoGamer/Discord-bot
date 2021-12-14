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

use JaxkDev\DiscordBot\Models\Channels\Stage;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class StageDelete extends Packet
{

    /** @var Stage */
    private $stage;

    public function __construct(Stage $stage)
    {
        parent::__construct();
        $this->stage = $stage;
    }

    public function getStage(): Stage
    {
        return $this->stage;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->stage
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->stage
        ] = unserialize($data);
    }
}
