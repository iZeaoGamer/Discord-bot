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

class RequestRemoveSelectMenu extends Packet
{

    /** @var MessageBuilder */
    private $response;

    /** @var string */
    private $channelId;

    /** @var string */
    private $labelOption;

    /** @var string|null */
    private $value;

    /** @var string|null */
    private $placeHolder;

    /** @var int|null */
    private $minValue;

    /** @var int|null */
    private $maxValue;

    /** @var bool */
    private $disabled;

    /** @var string|null */
    private $customId;



    public function __construct(MessageBuilder $response, string $channelId, string $labelOption, ?string $value, ?string $placeHolder, ?int $minValue, ?int $maxValue, bool $disabled = true, ?string $custom_id = null)
    {
        parent::__construct();
        $this->response = $response;
        $this->channelId = $channelId;
        $this->labelOption = $labelOption;
        $this->value = $value;
        $this->placeHolder = $placeHolder;
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        $this->disabled = $disabled;
        $this->customId = $custom_id;
    }
    public function getMessage(): MessageBuilder
    {
        return $this->response;
    }
    public function getChannelId(): string{
        return $this->channelId;
    }
    public function getOptionLabel(): string
    {
        return $this->labelOption;
    }
    public function getValue(): ?string
    {
        return $this->value;
    }
    public function getPlaceHolder(): ?string
    {
        return $this->placeHolder;
    }
    public function getMinValue(): ?int
    {
        return $this->minValue;
    }
    public function getMaxValue(): ?int
    {
        return $this->maxValue;
    }
    public function isDisabled(): bool
    {
        return $this->disabled;
    }
    public function getCustomId(): ?string
    {
        return $this->customId;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->response,
            $this->channelId,
            $this->labelOption,
            $this->value,
            $this->placeHolder,
            $this->minValue,
            $this->maxValue,
            $this->disabled,
            $this->customId
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->response,
            $this->channelId,
            $this->labelOption,
            $this->value,
            $this->placeHolder,
            $this->minValue,
            $this->maxValue,
            $this->disabled,
            $this->customId
        ] = unserialize($data);
    }
}
