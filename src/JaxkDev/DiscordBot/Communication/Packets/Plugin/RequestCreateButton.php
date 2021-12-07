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
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;

class RequestCreateButton extends Packet
{

    /** @var MessageBuilder */
    private $response;

    /** @var string */
    private $channelId;

    /** @var Button */
    private $button;

    /** @var bool */
    private $ephemeral;

    public function __construct(MessageBuilder $response, string $channelId, Button $button, bool $ephemeral)
    {
        parent::__construct();
        $this->response = $response;
        $this->channelId = $channelId;
        $this->button = $button;
        $this->ephemeral = $ephemeral;
    }
    public function getMessage(): MessageBuilder
    {
        return $this->response;
    }
    public function getChannelId(): string
    {
        return $this->channelId;
    }
    public function getButton(): Button
    {
        return $this->button;
    }
    public function isEphemeral(): bool{
        return $this->ephemeral;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->response,
            $this->channelId,
            $this->button,
            $this->ephemeral
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->response,
            $this->channelId,
            $this->button,
            $this->ephemeral
        ] = unserialize($data);
    }
}
