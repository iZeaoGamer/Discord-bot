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

class StageUpdate extends Packet
{

    /** @var Stage */
    private $stage;

    /** @var Stage|null */
    private $old;

    public function __construct(Stage $stage, ?Stage $old)
    {
        parent::__construct();
        $this->stage = $stage;
        $this->old = $old;
    }

    public function getStage(): Stage
    {
        return $this->stage;
    }
    public function getOldStage(): ?Stage
    {
        return $this->old;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->stage,
            $this->old
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->stage,
            $this->old
        ] = unserialize($data);
    }
}
