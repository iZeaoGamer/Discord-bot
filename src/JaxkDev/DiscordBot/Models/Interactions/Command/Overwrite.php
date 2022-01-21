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

class Overwrite implements \Serializable
{

    /** @var string */
    private $id;

    /** @var string */
    private $application_id;

    /** @var string */
    private $server_id;

    /** @var Permission[] */
    private $permissions;

    /** 
     * Overwrite Constructor
     * @param string                $id
     * @param string                $application_id
     * @param string                $server_id
     * @param Permission[]          $permissions
     * 
     */
    public function __construct(
        string $id,
        string $application_id,
        string $server_id,
        array $permissions
    ) {
        $this->setId($id);
        $this->setApplicationId($application_id);
        $this->setServerId($server_id);
        $this->setPermissions($permissions);
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
    public function getApplicationId(): string
    {
        return $this->application_id;
    }
    public function setApplicationId(string $application_id)
    {
        if (!Utils::validDiscordSnowflake($application_id)) {
            throw new \AssertionError("Application ID: {$application_id} is invalid!");
        }
        $this->application_id = $application_id;
    }
    public function getServerId(): string
    {
        return $this->server_id;
    }
    public function setServerId(string $server_id)
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            throw new \AssertionError("Server ID: {$server_id} is invalid!");
        }
        $this->server_id = $server_id;
    }
    public function getPermissions(): array
    {
        return $this->permissions;
    }
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }
    //----- Serialization -----//
    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->application_id,
            $this->server_id,
            $this->permissions
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->id,
            $this->application_id,
            $this->server_id,
            $this->permissions
        ] = unserialize($data);
    }
}
