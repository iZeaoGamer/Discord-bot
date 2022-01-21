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

namespace JaxkDev\DiscordBot\Models\Interactions\Command;


use JaxkDev\DiscordBot\Plugin\Utils;

class Permission implements \Serializable
{

    public const TYPE_ROLE = 1;
    public const TYPE_USER = 2;

    /** @var string */
    private $id;

    /** @var int */
    private $type;

    /** @var bool */
    private $permission;

    /** 
     * Permission Constructor
     *
     * @param string            $id
     * @param int               $type
     * @param bool              $permission
     * 
     */
    public function __construct(
        string $id,
        int $type,
        bool $permission
    ) {
        $this->setId($id);
        $this->setType($type);
        $this->setPermission($permission);
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id): void
    {
        if (!Utils::validDiscordSnowflake($id)) {
            throw new \AssertionError("ID: {$id} is invalid.");
        }
        $this->id = $id;
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function setType(int $type): void
    {
        if ($type < self::TYPE_ROLE or $type > self::TYPE_USER) {
            throw new \AssertionError("Permission type: {$type} is invalid. Must be either Role (1) or User (2).");
        }
        $this->type = $type;
    }
    public function isAllowed(): bool
    {
        return $this->permission;
    }
    public function isDenied(): bool
    {
        return !$this->permission;
    }
    public function setPermission(bool $permission): void
    {
        $this->permission = $permission;
    }
    //----- Serialization -----//
    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->type,
            $this->permission
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->id,
            $this->type,
            $this->permission
        ] = unserialize($data);
    }
}
