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

namespace JaxkDev\DiscordBot\Models;

use JaxkDev\DiscordBot\Plugin\Utils;

class WelcomeChannel implements \Serializable
{

    /** @var string */
    private $channelId;

    /** @var string */
    private $description;

    /** @var string|null */
    private $emojiId; //null if emoji not set.

    /** @var string|null */
    private $emojiName; //null if emoji not set.

    public function __construct(string $channel_id, string $description, ?string $emoji_id = null, ?string $emoji_name)
    {
        $this->setChannelId($channel_id);
        $this->setDescription($description);
        $this->setEmojiId($emoji_id);
        $this->setEmojiName($emoji_name);
    }
    public function getChannelId(): string
    {
        return $this->channelId;
    }
    public function setChannelId(string $channel_id): void
    {
        if (!Utils::validDiscordSnowflake($channel_id)) {
            throw new \AssertionError("Channel ID '{$channel_id}' is invalid.");
        }
        $this->channelId = $channel_id;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    public function getEmojiId(): ?string
    {
        return $this->emojiId;
    }
    public function setEmojiId(?string $emoji_id): void
    {
        if ($emoji_id !== null) {
            if (!Utils::validDiscordSnowflake($emoji_id)) {
                throw new \AssertionError("Emoji ID: '{$emoji_id}' is invalid.");
            }
        }
        $this->emojiId = $emoji_id;
    }
    public function getEmojiName(): ?string
    {
        return $this->emojiName;
    }
    public function setEmojiName(?string $emoji_name): void
    {
        $this->emojiName = $emoji_name;
    }



    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->channelId,
            $this->description,
            $this->emojiId,
            $this->emojiName
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->channelId,
            $this->description,
            $this->emojiId,
            $this->emojiName
        ] = unserialize($data);
    }
}
