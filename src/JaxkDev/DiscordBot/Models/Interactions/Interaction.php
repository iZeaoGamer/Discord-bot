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

namespace JaxkDev\DiscordBot\Models\Interactions;

use JaxkDev\DiscordBot\Models\Interactions\Request\InteractionData;

use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Models\User;

class Interaction implements \Serializable

{

    /** @var string */
    private $application_id;

    /** @var int */
    private $type;

    /** @var User */
    private $user;

    /** @var string|null */
    private $serverId; //null when not in a guild.

    /** @var string|null */
    private $channelId; //null when not in a channel.

    /** @var string|null */
    private $id; //null when creating

    /** @var InteractionData|null */
    private $data; //null when not selecting data associated with Interaction

    /** @var string|null */
    private $token; //null when creating

    /** @var int|null */
    private $version; //null when creating

    /** @var Message|null */
    private $message; //null when not using message components as interaction type.

    public function __construct(
        string $application_id,
        int $type,
        User $user,
        ?string $server_id,
        ?string $channel_id,
        ?string $id = null,
        ?InteractionData $data = null,
        ?string $token = null,
        ?int $version = null,
        ?Message $message = null
    ) {
        $this->setApplicationID($application_id);
        $this->setType($type);
        $this->setUser($user);
        $this->setServerId($server_id);
        $this->setChannelId($channel_id);
        $this->setId($id);
        $this->setInteractionData($data);
        $this->setToken($token);
        $this->setVersion($version);
        $this->setMessage($message);
    }

    public function setApplicationId(string $application_id): void
    {
        $this->application_id = $application_id;
    }
    public function getApplicationID(): string
    {
        return $this->application_id;
    }
    public function setType(int $type): void
    {
        $this->type = $type;
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function setServerId(?string $serverId): void
    {
        $this->serverId = $serverId;
    }
    public function getServerId(): ?string
    {
        return $this->serverId;
    }
    public function setChannelId(?string $channel_id): void
    {
        $this->channelId = $channel_id;
    }
    public function getChannelId(): ?string
    {
        return $this->channelId;
    }
    public function setId(?string $id): void
    {
        $this->id = $id;
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function setInteractionData(?InteractionData $data): void
    {
        $this->data = $data;
    }
    public function getInteractionData(): ?InteractionData
    {
        return $this->data;
    }
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
    public function getToken(): ?string
    {
        return $this->token;
    }
    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }
    public function getVersion(): ?int
    {
        return $this->version;
    }
    public function setMessage(?Message $message): void
    {
        $this->message = $message;
    }
    public function getMessage(): ?Message
    {
        return $this->message;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->application_id,
            $this->type,
            $this->user,
            $this->serverId,
            $this->channelId,
            $this->id,
            $this->data,
            $this->token,
            $this->version,
            $this->message
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->application_id,
            $this->type,
            $this->user,
            $this->serverId,
            $this->channelId,
            $this->id,
            $this->data,
            $this->token,
            $this->version,
            $this->message
        ] = unserialize($data);
    }
}
