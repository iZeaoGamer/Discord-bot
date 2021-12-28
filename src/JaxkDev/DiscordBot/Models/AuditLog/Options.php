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

namespace JaxkDev\DiscordBot\Models\AuditLog;

use JaxkDev\DiscordBot\Plugin\Utils;

class Options implements \Serializable
{

    public const OVERWRITTEN_TYPE_ROLE = 0;
    public const OVERWRITTEN_TYPE_MEMBER = 1;


    /** @var string|null */
    private $deletedMembersDays;

    /** @var string|null */
    private $membersRemoved;

    /** @var string|null */
    private $channelId;

    /** @var string|null */
    private $messageId;

    /** @var string|null */
    private $count;

    /** @var string|null */
    private $id;

    /** @var string|null */
    private $type;

    /** @var string|null */
    private $roleName;

    public function __construct(
        ?string $delete_member_days = null,
        ?string $members_removed = null,
        ?string $channel_id = null,
        ?string $message_id = null,
        ?string $count = null,
        ?string $id = null,
        ?string $type = null,
        ?string $role_name = null
    ) {
        $this->setDeletedMembersDays($delete_member_days);
        $this->setMembersRemoved($members_removed);
        $this->setChannelId($channel_id);
        $this->setMessageId($message_id);
        $this->setCount($count);
        $this->setId($id);
        $this->setType($type);
        $this->setRoleName($role_name);
    }
    public function getDeletedMembersDays(): ?string
    {
        return $this->deletedMembersDays;
    }
    public function setDeletedMembersDays(?string $deleted_members): void
    {
        $this->deletedMembersDays = $deleted_members;
    }
    public function getMembersRemoved(): ?string
    {
        return $this->membersRemoved;
    }
    public function setMembersRemoved(?string $removed_members): void
    {
        $this->membersRemoved = $removed_members;
    }
    public function getChannelId(): ?string
    {
        return $this->channelId;
    }
    public function setChannelId(?string $channel_id): void
    {
        if ($channel_id !== null) {
            if (!Utils::validDiscordSnowflake($channel_id)) {
                throw new \AssertionError("Channel ID: {$channel_id} is invalid.");
            }
        }
        $this->channelId = $channel_id;
    }
    public function getMessageId(): ?string
    {
        return $this->messageId;
    }
    public function setMessageId(?string $message_id): void
    {
        if ($message_id !== null) {
            if (!Utils::validDiscordSnowflake($message_id)) {
                throw new \AssertionError("Channel ID: {$message_id} is invalid.");
            }
        }
        $this->messageId = $message_id;
    }
    public function getCount(): ?string
    {
        return $this->count;
    }
    public function setCount(?string $count): void
    {
        $this->count = $count;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): void
    {
        if ($id !== null) {
            if (!Utils::validDiscordSnowflake($id)) {
                throw new \AssertionError("Server ID: {$id} is invalid!");
            }
        }
        $this->id = $id;
    }
    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(?string $type): void
    {
        if ($type !== null) {
            if (!in_array($type, [self::OVERWRITTEN_TYPE_ROLE, self::OVERWRITTEN_TYPE_MEMBER])) {
                throw new \AssertionError("Overwritten type: {$type} is invalid. It must be either role (0) or member (1).");
            }
        }
        $this->type = $type;
    }
    public function getRoleName(): ?string
    {
        return $this->roleName;
    }
    public function setRoleName(?string $roleName): void
    {
        $this->roleName = $roleName;
    }


    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->deletedMembersDays,
            $this->membersRemoved,
            $this->channelId,
            $this->messageId,
            $this->count,
            $this->id,
            $this->type,
            $this->roleName
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->deletedMembersDays,
            $this->membersRemoved,
            $this->channelId,
            $this->messageId,
            $this->count,
            $this->id,
            $this->type,
            $this->roleName
        ] = unserialize($data);
    }
}
