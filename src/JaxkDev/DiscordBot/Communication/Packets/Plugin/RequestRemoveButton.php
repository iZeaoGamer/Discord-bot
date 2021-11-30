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

use Discord\Builders\MessageBuilder;

class RequestRemoveButton extends Packet
{

    /** @var MessageBuilder */
    private $response;

    /** @var int */
    private $style;

    /** @var string */
    private $label;

    /** @var string */
    private $customId;

    /** @var bool */
    private $disabled;

    /** @var string|null */
    private $emoji; //null if clear.

    /** @var string|null */
    private $url; //null if button isn't a link button. 

    public function __construct(MessageBuilder $response, int $style, string $label, string $customId, bool $disabled, ?string $emoji = null, ?string $url = null)
    {
        parent::__construct();
        $this->response = $response;
        $this->style = $style;
        $this->label = $label;
        $this->customId = $customId;
        $this->disabled = $disabled;
        $this->emoji = $emoji;
        $this->url = $url;
    }
    public function getMessage(): MessageBuilder
    {
        return $this->response;
    }

    public function getStyle(): int
    {
        return $this->style;
    }
    public function getLabel(): string
    {
        return $this->label;
    }
    public function getCustomId(): string
    {
        return $this->customId;
    }
    public function isDisabled(): bool
    {
        return $this->disabled;
    }
    public function getEmoji(): ?string
    {
        return $this->emoji;
    }
    public function getURL(): ?string
    {
        return $this->url;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->response,
            $this->style,
            $this->label,
            $this->customId,
            $this->disabled,
            $this->emoji,
            $this->url
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->response,
            $this->style,
            $this->label,
            $this->customId,
            $this->disabled,
            $this->emoji,
            $this->url
        ] = unserialize($data);
    }
}
