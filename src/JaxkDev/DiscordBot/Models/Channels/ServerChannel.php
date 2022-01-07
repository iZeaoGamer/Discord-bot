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

namespace JaxkDev\DiscordBot\Models\Channels;

use JaxkDev\DiscordBot\Models\Permissions\ChannelPermissions;
use JaxkDev\DiscordBot\Plugin\Utils;

abstract class ServerChannel extends Channel
{

    /** @var string */
    protected $name;

    /** @var int */
    protected $position;

    /**
     * ['MemberID' => [AllowedPermissions|null, DeniedPermissions|null]]
     * @var Array<string, Array<null|ChannelPermissions>>
     */
    protected $member_permissions = [];

    /**
     * This includes the @everyone role, use the server ID as role ID to set the @everyone permissions.
     * ['roleID' => [AllowedPermissions|null, DeniedPermissions|null]]
     * @var Array<string, Array<null|ChannelPermissions>>
     */
    protected $role_permissions = [];

    /** @var string */
    protected $server_id;

    /** @var ?string Category ID | null when not categorised. */
    protected $category_id;

    /** @var string|null */
    protected $permissions; //Computed permissions for the invoking user in the channel, including overwrites, only included when part of the resolved data received on a slash command interaction.

    public function __construct(string $name, int $position, string $server_id, ?string $category_id = null, ?string $id = null,
    ?string $permissions = null)
    {
        parent::__construct($id);
        $this->setName($name);
        $this->setPosition($position);
        $this->setServerId($server_id);
        $this->setCategoryId($category_id);
        $this->setComputePermissions($permissions);
        //Note Channel and Role Permissions are not in constructor however, Permissions computing is.
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return Array<string, Array<null|ChannelPermissions>>
     */
    public function getAllMemberPermissions(): array
    {
        return $this->member_permissions;
    }

    /**
     * @param string $id
     * @return Array<null|ChannelPermissions>  [AllowedPerms|null, DeniedPerms|null]
     */
    public function getMemberPermissions(string $id): array
    {
        return $this->member_permissions[$id] ?? [null, null];
    }

    public function setAllowedMemberPermissions(string $id, ChannelPermissions $allowedPermissions): void
    {
        if (isset($this->member_permissions[$id])) {
            $this->member_permissions[$id][0] = $allowedPermissions;
        } else {
            $this->member_permissions[$id] = [$allowedPermissions, null];
        }
    }

    public function setDeniedMemberPermissions(string $id, ChannelPermissions $deniedPermissions): void
    {
        if (isset($this->member_permissions[$id])) {
            $this->member_permissions[$id][1] = $deniedPermissions;
        } else {
            $this->member_permissions[$id] = [null, $deniedPermissions];
        }
    }

    /**
     * @return Array<string, Array<null|ChannelPermissions>>
     */
    public function getAllRolePermissions(): array
    {
        return $this->role_permissions;
    }

    /**
     * @param string $id
     * @return Array<null|ChannelPermissions>  [AllowedPerms|null, DeniedPerms|null]
     */
    public function getRolePermissions(string $id): array
    {
        return $this->role_permissions[$id] ?? [null, null];
    }

    public function setAllowedRolePermissions(string $id, ChannelPermissions $allowedPermissions): void
    {
        if (isset($this->role_permissions[$id])) {
            $this->role_permissions[$id][0] = $allowedPermissions;
        } else {
            $this->role_permissions[$id] = [$allowedPermissions, null];
        }
    }

    public function setDeniedRolePermissions(string $id, ChannelPermissions $deniedPermissions): void
    {
        if (isset($this->role_permissions[$id])) {
            $this->role_permissions[$id][1] = $deniedPermissions;
        } else {
            $this->role_permissions[$id] = [null, $deniedPermissions];
        }
    }
    public function getComputePermissions(): ?string{
        return $this->permissions;
    }
    public function setComputePermissions(?string $permission): void{
        $this->permissions = $permission;
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

    public function getCategoryId(): ?string
    {
        return $this->category_id;
    }

    public function setCategoryId(?string $category_id): void
    {
        if ($category_id !== null and !Utils::validDiscordSnowflake($category_id)) {
            throw new \AssertionError("Category ID '$category_id' is invalid.");
        }
        $this->category_id = $category_id;
    }
}
