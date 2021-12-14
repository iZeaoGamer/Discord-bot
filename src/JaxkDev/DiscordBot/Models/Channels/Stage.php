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

class Stage
{

    /** @var string */
    private $serverId;

    /** @var string */
    private $channelId;

    /** @var string AKA Description. */
    private $topic;

    /** @var string|null */
    private $id; //null when creating.

    /** @var int */
    private $privacyLevel; //leave empty to make it public.

    /** @var bool */
    private $disableDiscovery;

    /**
     * TextChannel constructor.
     *
     * @param string      $server_id
     * @param string      $channel_id
     * @param int         $topic
     * @param string|null      $id
     * @param int         $privacy_level
     * @param bool    $disableDiscovery
     */
    public function __construct(
        string $server_id, 
        string $channel_id,
        string $topic,
        ?string $id = null,
        int $privacy_level = 1,
        bool $disableDiscovery = false
    ) {
        $this->setServerId($server_id);
        $this->setChannelId($channel_id);
        $this->setTopic($topic);
        $this->setId($id);
        $this->setPrivacyLevel($privacy_level);
        $this->setDisableDiscovery($disableDiscovery);
    }
    public function getServerId(): string{
        return $this->serverId;
    }
    public function setServerId(string $server_id){
        $this->serverId = $server_id;
    }
    public function getChannelId(): string{
        return $this->channelId;
    }
    public function setChannelId(string $channelId){
        $this->channelId = $channelId;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }
    public function getId(): ?string{
        return $this->id;
    }
    public function setId(?string $id): void{
        $this->id = $id;
    }
    public function getPrivacyLevel(): int{
        return $this->privacyLevel;
    }
    public function setPrivacyLevel(int $privacyLevel): void{
        if($privacyLevel < 1 or $privacyLevel > 2){
            throw new \AssertionError("Privacy Level {$privacyLevel} is invalid. It must be between type 1 and type 2.");
        }
        $this->privacyLevel = $privacyLevel;
    }
    public function isDisabled(): bool{
        return $this->disableDiscovery;
    }
    public function setDisableDiscovery(bool $disable){
        $this->disableDiscovery = $disable;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->serverId,
            $this->channelId,
            $this->topic,
            $this->id,
            $this->privacyLevel,
            $this->disableDiscovery
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->serverId,
            $this->channelId,
            $this->topic,
            $this->id,
            $this->privacyLevel,
            $this->disableDiscovery
        ] = unserialize($data);
    }
}
