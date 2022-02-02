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

namespace JaxkDev\DiscordBot\Models\Server;

class WelcomeScreen implements \Serializable
{

    /** @var string */
    private $description;

    /** @var WelcomeChannel[] */
    private $channels;


    /** WelcomeScreen Constructor.
     * 
     * @param string                    $description
     * @param WelcomeChannel[]          $channels
     * 
     */
    public function __construct(string $description, array $channels)
    {
        $this->setDescription($description);
        $this->setWelcomeChannels($channels);
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        if (strlen($description) > 140) {
            throw new \AssertionError("Invalid Description {$description} - Must be below 140 characters.");
        }
        $this->description = $description;
    }

    /** @return WelcomeChannel[] */
    public function getWelcomeChannels(): array
    {
        return $this->channels;
    }
    /** @param WelcomeChannel[] */
    public function setWelcomeChannels(array $channels): void
    {
        if (count($channels) > 5) {
            throw new \AssertionError("Maximum Welcome Channels exceeded. Must be below 5 welcome channels.");
        }
        $this->channels = $channels;
    }


    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->description,
            $this->channels
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->description,
            $this->channels
        ] = unserialize($data);
    }
}
