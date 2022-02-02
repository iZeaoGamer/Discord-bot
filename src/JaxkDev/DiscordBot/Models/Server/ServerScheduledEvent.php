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

namespace JaxkDev\DiscordBot\Models\Server;

use JaxkDev\DiscordBot\Plugin\Utils;

class ServerScheduledEvent implements \Serializable
{
    /** @var string */
    private $serverId;

    /** @var string */
    private $name;

    /** @var string|null */
    private $id; //null when creating.

    /** @var string|null */
    private $channelId; //null when entity type is set to EXTERNAL

    /** @var string|null */
    private $creatorId; //null when creating.

    /** @var string */
    private $description;

    /** @var int */
    private $startTimestamp;

    /** @var int */
    private $endTimestamp;

    /** @var int */
    private $privacyLevel;

    /** @var int */
    private $status;

    /** @var int */
    private $entityType;

    /** @var string|null */
    private $entityId; //null when creating.

    /** @var int */
    private $userCount;


    /** ServerScheduledEvent Constructor
     * 
     * @param string                $server_id
     * @param string                $name
     * @param string|null           $id
     * @param string|null           $channel_id
     * @param string|null           $creator_id
     * @param string|null           $description
     * @param int|null              $start
     * @param int|null              $end
     * @param int                   $privacy_level
     * @param int                   $status
     * @param int                   $entry_type
     * @param string|null           $entity_id
     * @param int                   $user_count
     * 
     */
    public function __construct(
        string $server_id,
        string $name,
        ?string $id = null,
        ?string $channel_id = null,
        ?string $creator_id = null,
        ?string $description = null,
        ?int $start = null,
        ?int $end = null,
        int $privacy_level = 2,
        int $status = 3,
        int $entity_type = 1,
        ?string $entity_id = null,
        int $user_count = 0
    ) {
        $this->setServerId($server_id);
        $this->setName($name);
        $this->setId($id);
        $this->setChannelId($channel_id);
        $this->setCreatorId($creator_id);
        $this->setDescription($description ?? "");
        $this->setStartTimestamp($start ?? time());
        $this->setEndTimestamp($end ?? time());
        $this->setPrivacyLevel($privacy_level);
        $this->setStatus($status);
        $this->setEntityType($entity_type);
        $this->setEntityId($entity_id);
        $this->setUserCount($user_count);
    }
    public function getServerId(): string
    {
        return $this->server_id;
    }

    public function setServerId(string $server_id): void
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            throw new \AssertionError("Server ID '$server_id' is invalid.");
        }
        $this->server_id = $server_id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        if (strlen($name) > 100) {
            throw new \AssertionError("The Scheduled Event Name limit reached. Must be atleast 100 characters long.");
        }
        $this->name = $name;
    }


    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): void
    {
        if ($id !== null) {
            if (!Utils::validDiscordSnowflake($id)) {
                throw new \AssertionError("ID '{$id}' is invalid.");
            }
        }
        $this->id = $id;
    }
    public function getChannelId(): ?string
    {
        return $this->channelId;
    }
    public function setChannelId(?string $channel_id): void
    {
        if ($channel_id !== null) {
            if (!Utils::validDiscordSnowflake($channel_id)) {
                throw new \AssertionError("Channel ID: '{$channel_id}' is invalid.");
            }
        }
        $this->channelId = $channel_id;
    }
    public function getCreatorId(): ?string
    {
        return $this->creatorId;
    }
    public function setCreatorId(?string $creator_id): void
    {
        if ($creator_id !== null) {
            if (!Utils::validDiscordSnowflake($creator_id)) {
                throw new \AssertionError("Creator ID: '{$creator_id}' is invalid.");
            }
        }
        $this->creatorId = $creator_id;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        if (strlen($description) > 1000) {
            throw new \AssertionError("Description limit reached. Must be atleast 1000 characters long.");
        }
        $this->description = $description;
    }
    public function getStartTimestamp(): int
    {
        return $this->startTimestamp;
    }
    public function setStartTimestamp(int $timestamp): void
    {
        $this->startTimestamp = $timestamp;
    }
    public function getEndTimestamp(): int
    {
        return $this->endTimestamp;
    }
    public function setEndTimestamp(int $timestamp): void
    {
        $this->endTimestamp = $timestamp;
    }
    public function getPrivacyLevel(): int
    {
        return $this->privacyLevel;
    }
    public function setPrivacyLevel(int $type): void
    {
        if ($type !== 2) {
            throw new \AssertionError("Privacy level must be PRIVACY_LEVEL_GUILD_ONLY  (2).");
        }
        $this->privacyLevel = $type;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    public function setStatus(int $status): void
    {
        if ($status < 1 or $status > 4) {
            throw new \AssertionError("Status {$status} is invalid. It must be either STATUS_SCHEDULED (1), STATUS_ACTIVE (2), STATUS_COMPLETED (3) or STATUS_CANCELED  (4)");
        }
        $this->status = $status;
    }
    public function getEntityType(): int
    {
        return $this->entityType;
    }
    public function setEntityType(int $type): void
    {
        if ($type < 1 or $type > 3) {
            throw new \AssertionError("Entity Type {$type} is invalid. It must be either ENTITY_TYPE_STAGE_INSTANCE (1), ENTITY_TYPE_VOICE (2) or ENTITY_TYPE_EXTERNAL (3).");
        }
        $this->entityType = $type;
    }
    public function getEntityId(): ?string
    {
        return $this->entityId;
    }
    public function setEntityId(?string $entity_id): void
    {
        if ($entity_id !== null) {
            if (!Utils::validDiscordSnowflake($entity_id)) {
                throw new \AssertionError("Entity ID: '{$entity_id}' is invalid.");
            }
        }
        $this->entityId = $entity_id;
    }
    public function getUserCount(): int
    {
        return $this->userCount;
    }
    public function setUserCount(int $userCount): void
    {
        $this->userCount = $userCount;
    }




    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->serverId,
            $this->name,
            $this->id,
            $this->channelId,
            $this->creatorId,
            $this->description,
            $this->startTimestamp,
            $this->endTimestamp,
            $this->privacyLevel,
            $this->status,
            $this->entityType,
            $this->entityId,
            $this->userCount
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->serverId,
            $this->name,
            $this->id,
            $this->channelId,
            $this->creatorId,
            $this->description,
            $this->startTimestamp,
            $this->endTimestamp,
            $this->privacyLevel,
            $this->status,
            $this->entityType,
            $this->entityId,
            $this->userCount
        ] = unserialize($data);
    }
}
