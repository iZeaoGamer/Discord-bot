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

class Option implements \Serializable

{

    /** @var string */
    private $name;

    /** @var int */
    private $type;

    /** @var mixed */
    private $value;

    /** @var bool */
    private $focused;

    public function __construct(string $name, int $type, mixed $value, bool $focused)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setValue($value);
        $this->setFocused($focused);
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
    public function getValue(): mixed
    {
        return $this->value;
    }
    public function setValue(mixed $value)
    {
        $this->value = $value;
    }
    public function isFocused(): bool{
        return $this->focused;
    }
    public function setFocused(bool $focused): void
    {
        $this->focused = $focused;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->type,
            $this->value,
            $this->focused,
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->type,
            $this->value,
            $this->focused,
        ] = unserialize($data);
    }
}
