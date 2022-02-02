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
use JaxkDev\DiscordBot\Models\Interactions\Interaction;
use Discord\Builders\MessageBuilder;
use JaxkDev\DiscordBot\Models\Channels\Messages\Embed\Embed;

class RequestFollowupMessage extends Packet
{

    /** @var Interaction */
    private $command;

    /** @var MessageBuilder */
    private $builder;

    /** @var string */
    private $content;

    /** @var Embed|null */
    private $embed;
    
    /** @var bool */
    private $ephemeral;


    public function __construct(Interaction $command, MessageBuilder $builder = null, string $content = "", ?Embed $embed = null, bool $ephemeral = false)
    {
        parent::__construct();
        $this->command = $command;
        $this->builder = $builder;
        $this->content = $content;
        $this->embed = $embed;
        $this->ephemeral = $ephemeral;
    }

    public function getInteraction(): Interaction
    {
        return $this->command;
    }
    public function getMessageBuilder(): ?MessageBuilder
    {
        return $this->builder;
    }
    public function getContent(): string
    {
        return $this->content;
    }
    public function getEmbed(): ?Embed
    {
        return $this->embed;
    }
    public function isEphemeral(): bool{
        return $this->ephemeral;
    }
    public function serialize(): ?string
    {
        return serialize([
            $this->UID,
            $this->command,
            $this->builder,
            $this->content,
            $this->embed,
            $this->ephemeral
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->UID,
            $this->command,
            $this->builder,
            $this->content,
            $this->embed,
            $this->ephemeral
        ] = unserialize($data);
    }
}
