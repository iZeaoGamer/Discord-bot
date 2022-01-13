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

class RequestUpdateWelcomeScreen extends Packet
{

    /** @var string */
    private $server_id;

    /** @var bool */
    private $enabled;

    /** @var array{"channel_id": string, "description": string, "emoji_id": ?string, "emoji_name": ?string} */
    private $options;

    /** @var string */
    private $description;

    /** Constructor.
     * @param string $server_id
     * @param bool $enabled
     * @param array{"channel_id": string, "description": string, "emoji_id": ?string, "emoji_name": ?string} $options
     */
    public function __construct(string $server_id, bool $enabled, array $options, string $description)
    {
        parent::__construct();
        $this->server_id = $server_id;
        $this->enabled = $enabled;
        $this->options = $options;
        $this->description = $description;
    }


    public function getServerId(): string
    {
        return $this->server_id;
    }
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
    /**
     * @return array{"channel_id": string, "description": string, "emoji_id": ?string, "emoji_name": ?string}
     */
    public function getOptions(): array
    {
        return $this->options;
    }
    public function getDescription(): string
    {
        return $this->description;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->server_id,
            $this->enabled,
            $this->options,
            $this->description
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->server_id,
            $this->enabled,
            $this->options,
            $this->description
        ] = unserialize($data);
    }
}
