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

use JaxkDev\DiscordBot\Models\Server\ServerTemplate;
use JaxkDev\DiscordBot\Communication\Packets\Packet;

class RequestTemplateUpdate extends Packet
{

    /** @var ServerTemplate */
    private $template;

    public function __construct(ServerTemplate $template)
    {
        parent::__construct();
        $this->template = $template;
    }

    public function getTemplate(): ServerTemplate
    {
        return $this->template;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->template
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->template
        ] = unserialize($data);
    }
}
