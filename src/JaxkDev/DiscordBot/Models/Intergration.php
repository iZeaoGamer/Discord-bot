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

use JaxkDev\DiscordBot\Models\OAuth\Application;
use JaxkDev\DiscordBot\Plugin\Utils;

class Intergration implements \Serializable

{

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var bool */
    private $enabled;

    /** @var bool|null */
    private $syncing; //null if not syncing.

    /** @var string|null */
    private $role_id; //null if role not set.

    /** @var bool|null */
    private $enabled_emoticons;

    /** @var int|null */
    private $expire_behavior;

    /** @var int|null */
    private $expire_grace_period;

    /** @var User|null */
    private $user;

    /** @var int|null */
    private $synced_at; //null when haven't synced yet.

    /** @var int|null */
    private $subscriber_count;

    /** @var bool|null */
    private $revoked;

    /** @var Application|null */
    private $application;

    /** @var string|null */
    private $server_id;

    /** Option Constructor
     * 
     * @param string                    $id
     * @param string                    $name
     * @param string                    $type
     * @param bool                      $enabled
     * @param bool|null                 $syncing
     * @param string|null               $role_id
     * @param bool|null                 $enable_emoticons 
     * @param int|null                  $expire_behavior
     * @param int|null                  $expire_grace_period
     * @param User|null                 $user
     * @param int|null                  $synced_at
     * @param int|null                  $subscriber_count 
     * @param bool|null                 $revoked
     * @param Application|null          $application 
     * @param string|null               $server_id
     * 
     */
    public function __construct(
        string $id,
        string $name,
        string $type,
        bool $enabled,
        ?bool $syncing = null,
        ?string $role_id = null,
        ?bool $enable_emoticons = null,
        ?int $expire_behavior = null,
        ?int $expire_grace_period = null,
        ?User $user = null,
        ?int $synced_at = null,
        ?int $subscriber_count = null,
        ?bool $revoked = null,
        ?Application $application = null,
        ?string $server_id = null
    ) {
        $this->setId($id);
        $this->setName($name);
        $this->setType($type);
        $this->setEnabled($enabled);
        $this->setSyncing($syncing);
        $this->setRoleId($role_id);
        $this->setEmoticonsEnabled($enable_emoticons);
        $this->setExpireBehavior($expire_behavior);
        $this->setExpireGracePeriod($expire_grace_period);
        $this->setUser($user);
        $this->setSyncedAt($synced_at);
        $this->setSubscriberCount($subscriber_count);
        $this->setRevoked($revoked);
        $this->setApplication($application);
        $this->setServerId($server_id);
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function setId(string $id)
    {
        if (!Utils::validDiscordSnowflake($id)) {
            throw new \AssertionError("Intergration ID: {$id} is invalid.");
        }
        $this->id = $id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function setType(int $type): void
    {
        $this->type = $type;
    }
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    public function isSyncing(): ?bool
    {
        return $this->syncing;
    }
    public function setSyncing(?bool $syncing)
    {
        $this->syncing = $syncing;
    }
    public function getRoleId(): ?string
    {
        return $this->role_id;
    }
    public function setRoleId(?string $role_id)
    {
        if ($role_id !== null and !Utils::validDiscordSnowflake($role_id)) {
            throw new \AssertionError("Role ID: {$role_id} is invalid.");
        }
        $this->role_id = $role_id;
    }
    public function isEmoticonsEnabled(): ?bool
    {
        return $this->enabled_emoticons;
    }
    public function setEmoticonsEnabled(?bool $enabled_emoticons)
    {
        $this->enabled_emoticons = $enabled_emoticons;
    }
    public function getExpireBehavior(): ?int
    {
        return $this->expire_behavior;
    }
    public function setExpireBehavior(?int $behavior)
    {
        if ($behavior !== null and $behavior < 0 or $behavior > 1) {
            throw new \AssertionError("Expire Behavior is invalid. It must be either 0 or 1.");
        }
        $this->expire_behavior = $behavior;
    }
    public function getExpireGracePeriod(): ?int
    {
        return $this->expire_grace_period;
    }
    public function setExpireGracePeriod(?int $expire_grace_period)
    {
        $this->expire_grace_period = $expire_grace_period;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
    public function getSyncedAt(): ?int
    {
        return $this->synced_at;
    }
    public function setSyncedAt(?int $synced_at): void
    {
        $this->synced_at = $synced_at;
    }
    public function getSubscriberCount(): ?int
    {
        return $this->subscriber_count;
    }
    public function setSubscriberCount(?int $subscriber_count): void
    {
        $this->subscriber_count = $subscriber_count;
    }
    public function isRevoked(): ?bool
    {
        return $this->revoked;
    }
    public function setRevoked(?bool $revoked): void
    {
        $this->revoked = $revoked;
    }
    public function getApplication(): ?Application
    {
        return $this->application;
    }
    public function setApplication(?Application $application): void
    {
        $this->application = $application;
    }
    public function getServerId(): ?string
    {
        return $this->server_id;
    }
    public function setServerId(?string $server_id): void
    {
        if ($server_id !== null and !Utils::validDiscordSnowflake($server_id)) {
            throw new \AssertionError("Server ID: {$server_id} is invalid!");
        }
        $this->server_id = $server_id;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->name,
            $this->type,
            $this->enabled,
            $this->syncing,
            $this->role_id,
            $this->enabled_emoticons,
            $this->expire_behavior,
            $this->expire_grace_period,
            $this->user,
            $this->synced_at,
            $this->subscriber_count,
            $this->revoked,
            $this->application,
            $this->server_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->id,
            $this->name,
            $this->type,
            $this->enabled,
            $this->syncing,
            $this->role_id,
            $this->enabled_emoticons,
            $this->expire_behavior,
            $this->expire_grace_period,
            $this->user,
            $this->synced_at,
            $this->subscriber_count,
            $this->revoked,
            $this->application,
            $this->server_id
        ] = unserialize($data);
    }
}
