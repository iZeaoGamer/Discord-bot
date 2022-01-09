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



use JaxkDev\DiscordBot\Models\Messages\Message;
use JaxkDev\DiscordBot\Models\User;
use JaxkDev\DiscordBot\Models\Interactions\Request\InteractionData;
use JaxkDev\DiscordBot\Plugin\Utils;

class Command implements \Serializable
{

     /** Slash commands; a text-based command that shows up when a user types / */
     public const CHAT_INPUT = 1;

     /** A UI-based command that shows up when you right click or tap on a user */
     public const USER = 2;
 
     /** A UI-based command that shows up when you right click or tap on a message */
     public const MESSAGE = 3;

    /** @var int */
    private $type;

    /** @var string */
    private $name;

    /** @var string */
    private $description; //empty when not using CHAT_INPUT Command type.

    /** @var Option[] */
    private $options;

    /** @var bool */
    private $default_permissions;

    /** @var string|null */
    private $id; //null when creating.

    /** @var string|null */
    private $application_id; //null if command isn't made by this application.

    /** @var string|null */
    private $server_id; //null when using global commands.

    /** @var string|null */
    private $version; //null when creating.


    /** Command Constructor 
     * @param int $type
     * @param string $name
     * @param string $description
     * @param bool $default_permission
     * @param Option[] $options
     * @param string|null $id
     * @param string|null $application_id
     * @param string|null $server_id
     * @param string|null $version
     */
    public function __construct(
        int $type,
        string $name,
        string $description,
        bool $default_permission,
        array $options = [],
        ?string $id = null,
        ?string $application_id = null,
        ?string $server_id = null,
        ?string $version = null
    ) {
        $this->setType($type);
        $this->setName($name);
        $this->setDescription($description);
        $this->setOptions($options);
        $this->setDefaultPermission($default_permission);
        $this->setId($id);
        $this->setApplicationId($application_id);
        $this->setServerId($server_id);
        $this->setVersion($version);
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function setType(int $type): void
    {
        if($type < self::CHAT_INPUT or $type > self::MESSAGE){
            throw new \AssertionError("Command type {$type} is invalid! It must be either Chat input (1), User (2) or Message (3).");
        }
        $this->type = $type;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        if (strlen($name) > 32) {
            throw new \AssertionError("Command Name: {$name} must be below 32 characters.");
        }
        $this->name = $name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        if (strlen($description) > 100) {
            throw new \AssertionError("Description: {$description} has a limit of 100 characters. Please reduce it.");
        }
        $this->description = $description;
    }
    /** @return Option[] */
    public function getOptions(): array
    {
        return $this->options;
    }

    /** @param Option[] $options */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
    public function isDefaultPermission(): bool
    {
        return $this->default_permission;
    }
    public function setDefaultPermission(bool $default): void
    {
        $this->default_permission = $default;
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): void
    {
        if ($id) {
            if (!Utils::validDiscordSnowflake($id)) {
                throw new \AssertionError("ID: {$id} is invalid.");
            }
        }
        $this->id = $id;
    }
    public function getApplicationID(): ?string
    {
        return $this->application_id;
    }
    public function setApplicationId(?string $application_id): void
    {
        if ($application_id) {
            if (!Utils::validDiscordSnowflake($application_id)) {
                throw new \AssertionError("ID: {$application_id} is invalid.");
            }
        }
        $this->application_id = $application_id;
    }
    public function getServerId(): ?string
    {
        return $this->serverId;
    }
    public function setServerId(?string $server_id): void
    {
        if ($server_id) {
            if (!Utils::validDiscordSnowflake($server_id)) {
                throw new \AssertionError("ID: {$server_id} is invalid.");
            }
        }
        $this->serverId = $server_id;
    }
    public function getVersion(): ?int
    {
        return $this->version;
    }
    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->type,
            $this->name,
            $this->description,
            $this->options,
            $this->default_permission,
            $this->id,
            $this->application_id,
            $this->serverId,
            $this->version
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->type,
            $this->name,
            $this->description,
            $this->options,
            $this->default_permission,
            $this->id,
            $this->application_id,
            $this->serverId,
            $this->version
        ] = unserialize($data);
    }
}
