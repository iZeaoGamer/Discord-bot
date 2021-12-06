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

use JaxkDev\DiscordBot\Models\Messages\Message;


class InteractionData implements \Serializable

{

    /** @var string|null */
    private $name; //null when not using slash commands.

    /** @var int|null */
    private $type; //null when using slash commands.

    /** @var string|null */
    private $id; //null when creating.

    /** @var array|null */
    private $values; //null when not selecting anything.

    /** @var string|null */
    private $customId; //null when using slash commands

    public function __construct(?string $name, ?int $type, ?string $id = null, ?array $values = null, ?string $custom_id = null)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setId($id);
        $this->setSelected($values);
        $this->setCustomId($custom_id);
    }
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setId(?string $id): void
    {
        $this->id = $id;
    }
    public function getId(): ?string
    {
        return $this->id;
    }
    public function setSelected(?array $values): void
    {
        $this->values = $values;
    }
    public function getSelected(): ?array
    {
        return $this->values;
    }
    public function setCustomId(?string $custom_id): void
    {
        $this->customId = $custom_id;
    }
    public function getCustomId(): ?string
    {
        return $this->customId;
    }
    public function setType(?int $type): void
    {
        $this->type = $type;
    }
    public function getType(): ?int
    {
        return $this->type;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->type,
            $this->id,
            $this->values,
            $this->customId
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->type,
            $this->id,
            $this->values,
            $this->customId
        ] = unserialize($data);
    }
}
