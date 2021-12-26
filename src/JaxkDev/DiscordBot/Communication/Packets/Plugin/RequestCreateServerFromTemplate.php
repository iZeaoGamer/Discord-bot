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
use JaxkDev\DiscordBot\Models\Server;

class RequestCreateServerFromTemplate extends Packet
{

    /** @var Server */
    private $server;

    /** @var string */
    private $template_code;
    
    /** @var string */
    private $server_name;

    /** @var string|null */
    private $server_icon;

    public function __construct(Server $server, string $template_code, string $server_name, ?string $server_icon = null)
    {
        parent::__construct();
        $this->server = $server;
        $this->template_code = $template_code;
        $this->server_name = $server_name;
        $this->server_icon = $server_icon;
    }

    public function getServer(): Server
    {
        return $this->server;
    }
    public function getTemplateCode(): string{
        return $this->template_code;
    }
    public function getServerName(): string
    {
        return $this->server_name;
    }
    public function getServerIcon(): ?string
    {
        return $this->server_icon;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->server,
            $this->template_code,
            $this->server_name,
            $this->server_icon
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->server,
            $this->template_code,
            $this->server_name,
            $this->server_icon
        ] = unserialize($data);
    }
}
