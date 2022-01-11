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
use JaxkDev\DiscordBot\Models\Interactions\Command\Command;
use JaxkDev\DiscordBot\Models\Interactions\Command\Permission;
use Discord\Builders\MessageBuilder;

class RequestCreateCommand extends Packet
{

    /** @var Command */
    private $command;

    /** @var Permission[] */
    private $permissions;


    public function __construct(Command $command, array $permissions)
    {
        parent::__construct();
        $this->command = $command;
        $this->permissions = $permissions;
    }

    public function getCommand(): Command
    {
        return $this->command;
    }
    /** @return Permission[] */
    public function getPermissions(): array{
        return $this->permissions;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->command,
            $this->permissions
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->command,
            $this->permissions
        ] = unserialize($data);
    }
}
