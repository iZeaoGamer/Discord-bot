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

namespace JaxkDev\DiscordBot\Models\Server\AuditLog;

use JaxkDev\DiscordBot\Plugin\Utils;

class Entry implements \Serializable
{
    // AUDIT LOG ENTRY TYPES
    public const GUILD_UPDATE = 1;
    public const CHANNEL_CREATE = 10;
    public const CHANNEL_UPDATE = 11;
    public const CHANNEL_DELETE = 12;
    public const CHANNEL_OVERWRITE_CREATE = 13;
    public const CHANNEL_OVERWRITE_UPDATE = 14;
    public const CHANNEL_OVERWRITE_DELETE = 15;
    public const MEMBER_KICK = 20;
    public const MEMBER_PRUNE = 21;
    public const MEMBER_BAN_ADD = 22;
    public const MEMBER_BAN_REMOVE = 23;
    public const MEMBER_UPDATE = 24;
    public const MEMBER_ROLE_UPDATE = 25;
    public const MEMBER_MOVE = 26;
    public const MEMBER_DISCONNECT = 27;
    public const BOT_ADD = 28;
    public const ROLE_CREATE = 30;
    public const ROLE_UPDATE = 31;
    public const ROLE_DELETE = 32;
    public const INVITE_CREATE = 40;
    public const INVITE_UPDATE = 41;
    public const INVITE_DELETE = 42;
    public const WEBHOOK_CREATE = 50;
    public const WEBHOOK_UPDATE = 51;
    public const WEBHOOK_DELETE = 52;
    public const EMOJI_CREATE = 60;
    public const EMOJI_UPDATE = 61;
    public const EMOJI_DELETE = 62;
    public const MESSAGE_DELETE = 72;
    public const MESSAGE_BULK_DELETE = 63;
    public const MESSAGE_PIN = 74;
    public const MESSAGE_UNPIN = 75;
    public const INTEGRATION_CREATE = 80;
    public const INTEGRATION_UPDATE = 81;
    public const INTEGRATION_DELETE = 82;
    public const STAGE_INSTANCE_CREATE = 83;
    public const STAGE_INSTANCE_UPDATE = 84;
    public const STAGE_INSTANCE_DELETE = 85;
    public const STICKER_CREATE = 90;
    public const STICKER_UPDATE = 91;
    public const STICKER_DELETE = 92;
    public const GUILD_SCHEDULED_EVENT_CREATE = 100;
    public const GUILD_SCHEDULED_EVENT_UPDATE = 101;
    public const GUILD_SCHEDULED_EVENT_DELETE = 102;
    public const THREAD_CREATE = 110;
    public const THREAD_UPDATE = 111;
    public const THREAD_DELETE = 112;
    // AUDIT LOG ENTRY TYPES

    public const VALID_ACTION_TYPES = [
        self::GUILD_UPDATE,
        self::CHANNEL_CREATE,
        self::CHANNEL_UPDATE,
        self::CHANNEL_DELETE,
        self::CHANNEL_OVERWRITE_CREATE,
        self::CHANNEL_OVERWRITE_UPDATE,
        self::CHANNEL_OVERWRITE_DELETE,
        self::MEMBER_KICK,
        self::MEMBER_PRUNE,
        self::MEMBER_BAN_ADD,
        self::MEMBER_BAN_REMOVE,
        self::MEMBER_UPDATE,
        self::MEMBER_ROLE_UPDATE,
        self::MEMBER_MOVE,
        self::MEMBER_DISCONNECT,
        self::BOT_ADD,
        self::ROLE_CREATE,
        self::ROLE_UPDATE,
        self::ROLE_DELETE,
        self::INVITE_CREATE,
        self::INVITE_UPDATE,
        self::INVITE_DELETE,
        self::WEBHOOK_CREATE,
        self::WEBHOOK_UPDATE,
        self::WEBHOOK_DELETE,
        self::EMOJI_CREATE,
        self::EMOJI_UPDATE,
        self::EMOJI_DELETE,
        self::MESSAGE_DELETE,
        self::MESSAGE_BULK_DELETE,
        self::MESSAGE_PIN,
        self::MESSAGE_UNPIN,
        self::INTEGRATION_CREATE,
        self::INTEGRATION_UPDATE,
        self::INTEGRATION_DELETE,
        self::STAGE_INSTANCE_CREATE,
        self::STAGE_INSTANCE_UPDATE,
        self::STAGE_INSTANCE_DELETE,
        self::STICKER_CREATE,
        self::STICKER_UPDATE,
        self::STICKER_DELETE,
        self::GUILD_SCHEDULED_EVENT_CREATE,
        self::GUILD_SCHEDULED_EVENT_UPDATE,
        self::GUILD_SCHEDULED_EVENT_DELETE,
        self::THREAD_CREATE,
        self::THREAD_UPDATE,
        self::THREAD_DELETE,


    ];
    // AUDIT LOG ENTRY TYPES

    /** @var string */
    private $id;

    /** @var string */
    private $userId;

    /** @var string */
    private $targetId; //a entity id, e.g a role, webhook, user, etc.

    /** @var int */
    private $action_type;

    /** @var Options */
    private $option;

    /** @var string */
    private $reason;

    /** @var Change[] */
    private $changes = [];


    /** Entry Constructor.
     * 
     * @param string    $id
     * @param string    $userId
     * @param string    $target_id
     * @param int       $action_type
     * @param Options   $options
     * @param string    $reason
     * @param Change[]  $changes
     * 
     */
    public function __construct(
        string $id,
        string $userId,
        string $target_id,
        int $action_type,
        Options $options,
        string $reason,
        array $changes
    ) {
        $this->setId($id);
        $this->setUserId($userId);
        $this->setTargetId($target_id);
        $this->setActionType($action_type);
        $this->setOption($options);
        $this->setReason($reason);
        $this->setChanges($changes);
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id): void
    {
        if (!Utils::validDiscordSnowflake($id)) {
            throw new \AssertionError("Server ID: {$id} is invalid!");
        }
        $this->id = $id;
    }
    public function getUserId(): string
    {
        return $this->userId;
    }
    public function setUserId(string $userId): void
    {
        if (!Utils::validDiscordSnowflake($userId)) {
            throw new \AssertionError("User ID: {$userId} is invalid!");
        }
        $this->userId = $userId;
    }
    public function getTargetId(): string
    {
        return $this->targetId;
    }
    public function setTargetId(string $target_id): void
    {
        $this->targetId = $target_id;
    }
    public function getActionType(): int
    {
        return $this->actionType;
    }
    public function setActionType(int $action_type): void
    {
        if (!in_array($action_type, self::VALID_ACTION_TYPES)) {
            throw new \AssertionError("Action type: {$action_type} does not exist. Are you sure this is the correct action type you're requiring?");
        }
        $this->actionType = $action_type;
    }
    public function getOption(): Options
    {
        return $this->option;
    }
    public function setOption(Options $option): void
    {
        $this->option = $option;
    }
    public function getReason(): string
    {
        return $this->reason;
    }
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /** @return Change[] */
    public function getChanges(): array{
        return $this->changes;
    }

    /** @param Change[] $changes */
    public function setChanges(array $changes){
        $this->changes = $changes;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->user_id,
            $this->targetId,
            $this->actionType,
            $this->option,
            $this->reason,
            $this->changes
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->id,
            $this->user_id,
            $this->targetId,
            $this->actionType,
            $this->option,
            $this->reason,
            $this->changes
        ] = unserialize($data);
    }
}
