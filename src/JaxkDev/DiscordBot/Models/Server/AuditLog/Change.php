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

namespace JaxkDev\DiscordBot\Models\Server\AuditLog;

use Discord\Parts\Guild\AuditLog\Change as DiscordChange;

class Change implements \Serializable
{

    /** Change Constructor.
     * 
     * @param mixed $new_value
     * @param mixed $old_value
     * @param string $key
     * 
     */
    public function __construct(
        mixed $new_value,
        mixed $old_value,
        string $key
    ) {
        $this->setNewValue($new_value);
        $this->setOldValue($old_value);
        $this->setKey($key);
    }

    public function getNewValue(): mixed
    {
        return $this->new_value;
    }
    public function setNewValue(mixed $new_value)
    {
        $this->new_value = $new_value;
    }
    public function getOldValue(): mixed
    {
        return $this->old_value;
    }
    public function setOldValue(mixed $old_value)
    {
        $this->old_value = $old_value;
    }
    public function getKey(): string
    {
        return $this->key;
    }
    public function setKey(string $key): void
    {
        if (!in_array($key, DiscordChange::KEY_TYPE)) {

            /** @var string[] $similarKeys */
            $similarKeys = [];
            foreach (DiscordChange::KEY_TYPE as $typeKey) {
                if (strpos($key, $typeKey) !== false) {
                    $similarKeys[] = $typeKey;
                }
            }
            throw new \AssertionError("Change Key: {$key} does not exist! We've found some similar change keys that you could be looking for: " . implode(", ", $similarKeys));
        }
        $this->key = $key;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->new_value,
            $this->old_value,
            $this->key
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->new_value,
            $this->old_value,
            $this->key
        ] = unserialize($data);
    }
}
