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


class Option implements \Serializable

{

    /** @var string */
    private $name;

    /** @var int */
    private $type;

    /** @var mixed */
    private $value;

    public function __construct(string $name, int $type, mixed $value)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setValue($value);
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setType(int $type): void
    {
        $this->type = $type;
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function setValue(mixed $value)
    {
        $this->value = $value;
    }
    public function getValue(): mixed
    {
        return $this->value;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->type,
            $this->value
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->type,
            $this->value
        ] = unserialize($data);
    }
}
