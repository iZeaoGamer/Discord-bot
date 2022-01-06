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
use JaxkDev\DiscordBot\Models\Channels\ServerChannel;
use JaxkDev\DiscordBot\Models\Interactions\Command\Command;

class RequestUpdateCommand extends Packet
{

    /** @var Command */
    private $command;

    public function __construct(Command $command)
    {
        parent::__construct();
        $this->command = $command;
    }

    public function getCommand(): Command
    {
        return $this->command;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->command
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->command
        ] = unserialize($data);
    }
}
