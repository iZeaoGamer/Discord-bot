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

use JaxkDev\DiscordBot\Plugin\Utils;

use JaxkDev\DiscordBot\Models\User;
use JaxkDev\DiscordBot\Models\Emoji;


class Reaction implements \Serializable
{

    /** @var string */
    private $messageId;

    /** @var string */
    private $channelId;

    /** @var Emoji */
    private $emoji;

    /** @var string|null */
    private $serverId; //null if reactions are outside of the server.

    /** @var string|null */
    private $id; //null when creating.

    /** @var int|null */
    private $count; //null when creating.

    /** @var bool|null */
    private $me; //null when creating.


    public function __construct(
        string $message_id,
        string $channel_id,
        Emoji $emoji,
        ?string $server_id = null,
        ?string $id = null,
        ?int $count = null,
        ?bool $me = null
    ) {
        $this->setMessageId($message_id);
        $this->setChannelId($channel_id);
        $this->setEmoji($emoji);
        $this->setServerId($server_id);
        $this->setId($id);
        $this->setCount($count);
        $this->setMe($me);
    }
    public function getMessageId(): string
    {
        return $this->messageId;
    }
    public function setMessageId(string $messageId): void
    {
        if (!Utils::validDiscordSnowflake($messageId)) {
            throw new \AssertionError("Message ID: {$messageId} is invalid.");
        }
        $this->messageId = $messageId;
    }
    public function getChannelId(): string
    {
        return $this->channelId;
    }
    public function setChannelId(string $channelId): void
    {
        $this->channelId = $channelId;
    }
    public function getEmoji(): Emoji
    {
        return $this->emoji;
    }
    public function setEmoji(Emoji $emoji): void
    {
        $this->emoji = $emoji;
    }
    public function getServerId(): ?string
    {
        return $this->serverId;
    }
    public function setServerId(?string $server_id): void
    {
        if ($server_id !== null) {
            if (!Utils::validDiscordSnowflake($server_id)) {
                throw new \AssertionError("Server ID: {$server_id} is invalid.");
            }
        }
        $this->serverId = $server_id;
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): void
    {
        if ($id !== null) {
            if (!Utils::validDiscordSnowflake($id)) {
                throw new \AssertionError("Reaction ID: {$id} invalid.");
            }
        }
        $this->id = $id;
    }
    public function getCount(): ?int
    {
        return $this->count;
    }
    public function setCount(?int $count): void
    {
        $this->count = $count;
    }
    public function isBotReacted(): ?bool
    {
        return $this->me;
    }
    public function setMe(?bool $me): void
    {
        $this->me = $me;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->messageId,
            $this->channelId,
            $this->emoji,
            $this->serverId,
            $this->id,
            $this->count,
            $this->me
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->messageId,
            $this->channelId,
            $this->emoji,
            $this->serverId,
            $this->id,
            $this->count,
            $this->me
        ] = unserialize($data);
    }
}
