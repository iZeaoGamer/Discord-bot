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

class ServerTemplate implements \Serializable
{
    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var string */
    private $serverId;

    /** @var string|null */
    private $code;

    /** @var int */
    private $usage_count;

    /** @var string|null */
    private $creatorId;

    /** @var int */
    private $createdTimestamp;

    /** @var int */
    private $updatedTimestamp;

    /** @var bool */
    private $dirty;

    /** ServerTemplate Constructor
     * 
     * @param string                $name
     * @param string|null           $description
     * @param string                $server_id
     * @param string|null           $code
     * @param int                   $usage_count
     * @param string|null           $creator_id
     * @param int|null              $created_at
     * @param int|null              $updated_at
     * @param bool                  $is_dirty
     * 
     */
    public function __construct(
        string $name,
        ?string $description,
        string $server_id,
        ?string $code = null,
        int $usage_count = 0,
        ?string $creator_id = null,
        ?int $created_at = null,
        ?int $updated_at = null,
        bool $is_dirty = false
    ) {
        $this->setName($name);
        $this->setDescription($description ?? "");
        $this->setServerId($server_id);
        $this->setCode($code);
        $this->setUsageCount($usage_count);
        $this->setCreatorId($creator_id);
        $this->setCreatedTimestamp($created_at ?? time());
        $this->setUpdatedTimestamp($updated_at ?? time());
        $this->setDirty($is_dirty);
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        if (strlen($description) > 120) {
            throw new \AssertionError("Description limit reached. Must be atleast 120 characters long.");
        }
        $this->description = $description;
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
    public function getCode(): ?string
    {
        return $this->code;
    }
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
    public function getUsageCount(): int
    {
        return $this->usage_count;
    }
    public function setUsageCount(int $usage_count): void
    {
        $this->usage_count = $usage_count;
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
    public function getCreatedTimestamp(): int
    {
        return $this->createdTimestamp;
    }
    public function setCreatedTimestamp(int $time_stamp): void
    {
        $this->createdTimestamp = $time_stamp;
    }
    public function getUpdatedTimestamp(): int
    {
        return $this->updatedTimestamp;
    }
    public function setUpdatedTimestamp(int $time_stamp): void
    {
        $this->updatedTimestamp = $time_stamp;
    }
    public function isDirty(): bool
    {
        return $this->dirty;
    }
    public function setDirty(bool $dirty): void
    {
        $this->dirty = $dirty;
    }


    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->description,
            $this->server_id,
            $this->code,
            $this->usage_count,
            $this->creatorId,
            $this->createdTimestamp,
            $this->updatedTimestamp,
            $this->dirty
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->description,
            $this->server_id,
            $this->code,
            $this->usage_count,
            $this->creatorId,
            $this->createdTimestamp,
            $this->updatedTimestamp,
            $this->dirty
        ] = unserialize($data);
    }
}
