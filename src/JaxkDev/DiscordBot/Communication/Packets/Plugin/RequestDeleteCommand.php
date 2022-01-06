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

class RequestDeleteCommand extends Packet
{

    /** @var string */
    private $command;

    /** 
     * With this parameter, you can directly remove a server command from that id, if it's a guild command type.
     * Make this null if using global commands.
     */
    /** @var string|null */
    private $server_id; 

    public function __construct(string $command, ?string $server_id = null)
    {
        parent::__construct();
        $this->command = $command;
        $this->server_id = $server_id;
    }

    public function getId(): string
    {
        return $this->command;
    }
    public function getServerId(): ?string{
        return $this->server_id;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->command,
            $this->server_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->command,
            $this->server_id
        ] = unserialize($data);
    }
}
