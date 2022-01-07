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

namespace JaxkDev\DiscordBot\Models\Messages;

use JaxkDev\DiscordBot\Models\Interactions\Interaction;
use JaxkDev\DiscordBot\Models\Messages\Embed\Embed;
use JaxkDev\DiscordBot\Plugin\Utils;
use Discord\Builders\Components\Component;



class CommandMessage extends Message implements \Serializable
{

    /** @var Interaction|null */
    protected $interaction;

    /**
     * Message constructor.
     *
     * @param string           $channel_id
     * @param string|null      $id
     * @param string           $content
     * @param Embed|null       $embed
     * @param string|null      $author_id
     * @param string|null      $server_id
     * @param float|null       $timestamp
     * @param Attachment[]     $attachments
     * @param bool             $everyone_mentioned
     * @param string[]         $users_mentioned
     * @param string[]         $roles_mentioned
     * @param string[]         $channels_mentioned
     * @param string[]         $stickers
     * @param string|null      $link
     * @param bool             $tts
     * @param Interaction|null $interaction
     * @param string|null      $application_id
     * @param Component[]      $components
     */
    public function __construct(
        string $channel_id,
        ?string $id = null,
        string $content = "",
        ?Embed $embed = null,
        ?string $author_id = null,
        ?string $server_id = null,
        ?float $timestamp = null,
        array $attachments = [],
        bool $everyone_mentioned = false,
        array $users_mentioned = [],
        array $roles_mentioned = [],
        array $channels_mentioned = [],
        array $stickers = [],
        ?string $link = null,
        bool $tts = false,
        ?Interaction $interaction = null,
        ?string $application_id = null,
        array $components = []
    ) {
        parent::__construct(
            $channel_id,
            $id,
            $content,
            $embed,
            $author_id,
            $server_id,
            $timestamp,
            $attachments,
            $everyone_mentioned,
            $users_mentioned,
            $roles_mentioned,
            $channels_mentioned,
            $stickers,
            $link,
            $tts
        );
        $this->setInteraction($interaction);
        $this->setApplicationId($application_id);
        $this->setComponents($components);
    }
    /** @return Interaction|null */
    public function getInteraction(): ?Interaction
    {
        return $this->interaction;
    }
    public function setInteraction(?Interaction $interaction): void
    {
        $this->interaction = $interaction;
    }

    /** @return string|null */
    public function getApplicationId(): ?string
    {
        return $this->application_id;
    }
    public function setApplicationId(?string $application_id): void
    {
        if ($application_id) {
            if (!Utils::validDiscordSnowflake($application_id)) {
                throw new \AssertionError("Application ID: {$application_id} is invalid.");
            }
        }
        $this->application_id = $application_id;
    }

    /** @return Component[] */
    public function getComponents(): array
    {
        return $this->components;
    }
    /** @param Component[] $components */
    public function setComponents(array $components): void
    {
        foreach ($components as $component) {
            if (!$component instanceof Component) {
                throw new \AssertionError("Component must be an instanceof Component");
            }
        }
        $this->components = $components;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->content,
            $this->embed,
            $this->author_id,
            $this->channel_id,
            $this->server_id,
            $this->timestamp,
            $this->attachments,
            $this->everyone_mentioned,
            $this->users_mentioned,
            $this->roles_mentioned,
            $this->channels_mentioned,
            $this->stickers,
            $this->link,
            $this->tts,
            $this->interaction,
            $this->application_id,
            $this->components
        ]);
    }
    public function unserialize($data): void
    {
        [
            $this->id,
            $this->content,
            $this->embed,
            $this->author_id,
            $this->channel_id,
            $this->server_id,
            $this->timestamp,
            $this->attachments,
            $this->everyone_mentioned,
            $this->users_mentioned,
            $this->roles_mentioned,
            $this->channels_mentioned,
            $this->stickers,
            $this->link,
            $this->tts,
            $this->interaction,
            $this->application_id,
            $this->components
        ] = unserialize($data);
    }
}
