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

namespace JaxkDev\DiscordBot\Models\Interactions\Request;

use JaxkDev\DiscordBot\Models\Interactions\Request\Resolved;

use JaxkDev\DiscordBot\Plugin\Utils;

class InteractionData implements \Serializable

{
    /** @var string|null */
    private $name; //null when using component types.

    /** @var int|null */
    private $type; //null when using component types.

    /** @var int|null */
    private $component_type; //null when using slash commands.

    /** @var string|null */
    private $id; //null when creating.

    /** @var array|null */
    private $values; //null when not selecting anything.

    /** @var string|null */
    private $customId; //null when using slash commands

    /** @var Resolved|null */
    private $resolved;

    /** @var string|null */
    private $target_id;

    /** @var string|null */
    private $server_id;

    /** @var Option[]|null */
    private $options;

    /** @var object[]|null */
    private $components;

    /** InteractionData Constructor
     * @param string|null                   $name
     * @param int|null                      $type
     * @param int|null                      $component_type
     * @param string|null                   $id
     * @param string[]|null                 $values
     * @param string|null                   $custom_id
     * @param Resolved|null                 $resolved
     * @param string|null                   $target_id
     * @param string|null                   $server_id
     * @param Option[]|null                 $options
     * @param object[]|null                 $components
     * 
     */
    public function __construct(
        ?string $name,
        ?int $type,
        ?int $component_type = null,
        ?string $id = null,
        ?array $values = null,
        ?string $custom_id = null,
        ?Resolved $resolved = null,
        ?string $target_id = null,
        ?string $server_id = null,
        ?array $options = null,
        ?array $components = null
    ) {
        $this->setName($name);
        $this->setType($type);
        $this->setComponentType($component_type);
        $this->setId($id);
        $this->setSelected($values);
        $this->setCustomId($custom_id);
        $this->setResolved($resolved);
        $this->setTargetId($target_id);
        $this->setServerId($server_id);
        $this->setOptions($options);
        $this->setComponents($components);
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /** @return string[]|null */
    public function getSelected(): ?array
    {
        return $this->values;
    }

    /** @param string[]|null $values */
    public function setSelected(?array $values): void
    {
        $this->values = $values;
    }
    public function getCustomId(): ?string
    {
        return $this->customId;
    }
    public function setCustomId(?string $custom_id): void
    {
        $this->customId = $custom_id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }
    public function setType(?int $type): void
    {
        $this->type = $type;
    }
    public function setComponentType(?int $type): void
    {
        $this->component_type = $type;
    }

    public function getComponentType(): ?int
    {
        return $this->component_type;
    }

    public function getResolved(): ?Resolved
    {
        return $this->resolved;
    }
    public function setResolved(?Resolved $resolve): void
    {
        $this->resolved = $resolve;
    }
    public function getTargetId(): ?string
    {
        return $this->target_id;
    }
    public function setTargetId(?string $target_id): void
    {
        if ($target_id) {
            if (!Utils::validDiscordSnowflake($target_id)) {
                throw new \AssertionError("Target ID: {$target_id} is invalid.");
            }
        }
        $this->target_id = $target_id;
    }
    public function getServerId(): ?string
    {
        return $this->server_id;
    }
    public function setServerId(?string $server_id): void
    {
        if ($server_id) {
            if (!Utils::validDiscordSnowflake($server_id)) {
                throw new \AssertionError("Server ID: {$server_id} is invalid!");
            }
        }
        $this->server_id = $server_id;
    }

    /** @return Option[]|null */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /** @param Option[]|null $options */
    public function setOptions(?array $options): void
    {
        $this->options = $options;
    }

    /** @return object[]|null */
    public function getComponents(): ?array
    {
        return $this->components;
    }
    /** @param object[]|null $components 
     * 
     */
    public function setComponents(?array $components): void
    {
        $this->components = $components;
    }


    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->type,
            $this->component_type,
            $this->id,
            $this->values,
            $this->customId,
            $this->resolved,
            $this->target_id,
            $this->server_id,
            $this->options
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->type,
            $this->component_type,
            $this->id,
            $this->values,
            $this->customId,
            $this->resolved,
            $this->target_id,
            $this->server_id,
            $this->options,
        ] = unserialize($data);
    }
}
