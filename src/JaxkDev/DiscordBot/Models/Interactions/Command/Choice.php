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

class Choice implements \Serializable
{
    /** @var string */
    private $name;

    /** @var mixed */
    private $value;

    /** 
     * Choice Constructor
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, mixed $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        if (strlen($name) > 100) {
            throw new \AssertionError("Choice name: {$name} cannot be more than 100 characters long.");
        }
        $this->name = $name;
    }
    public function getValue(): mixed
    {
        return $this->value;
    }
    public function setValue(mixed $value): void
    {
        if (is_string($value) && strlen($value) > 100) {
            throw new \AssertionError("Choice Value: {$value} cannot be more than 100 characters long.");
        }
        $this->value = $value;
    }
    //----- Serialization -----//
    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->value
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->value
        ] = unserialize($data);
    }
}
