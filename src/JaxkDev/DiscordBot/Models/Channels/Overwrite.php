<?php

namespace JaxkDev\DiscordBot\Models\Channels;

use JaxkDev\DiscordBot\Plugin\Utils;

use JaxkDev\DiscordBot\Models\Permissions\ChannelPermissions;



class Overwrite implements \Serializable
{

    /** @var string */
    protected $channelId;

    /** @var int */
    protected $type;

    /** @var ChannelPermissions|null */
    protected $allow;

    /** @var ChannelPermissions|null */
    protected $deny;

    /** @var string|null */
    protected $id;


    /** Overwrite Constructor
     *
     * @param string                            $channel_id
     * @param int                               $type
     * @param ChannelPermissions|null           $allow
     * @param ChannelPermissions|null           $deny
     * @param string|null                       $id
     * 
     */
    public function __construct(string $channel_id, int $type, ?ChannelPermissions $allow = null, ?ChannelPermissions $deny = null, ?string $id = null)
    {
        $this->setChannelId($channel_id);
        $this->setType($type);
        $this->setAllowPermissions($allow);
        $this->setDenyPermissions($deny);
        $this->setId($id);
    }
    public function getChannelId(): string
    {
        return $this->channelId;
    }

    public function setChannelId(string $channel_id): void
    {
        if ($channel_id !== null) {
            if (!Utils::validDiscordSnowflake($channel_id)) {
                throw new \AssertionError("Invalid Sticker Id: {$channel_id}");
            }
        }
        $this->channelId = $channel_id;
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function setType(int $type): void
    {
        if ($type < 0 or $type > 1) {
            throw new \AssertionError("Invalid Overwrite type. Must only be role (0) or Member (1).");
        }
        $this->type = $type;
    }
    public function getAllowPermissions(): ?ChannelPermissions
    {
        return $this->allow;
    }
    public function setAllowPermissions(?ChannelPermissions $allow): void
    {
        $this->allow = $allow;
    }
    public function getDenyPermissions(): ?ChannelPermissions
    {
        return $this->deny;
    }
    public function setDenyPermissions(?ChannelPermissions $deny): void
    {
        $this->deny = $deny;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        if ($id !== null) {
            if (!Utils::validDiscordSnowflake($id)) {
                throw new \AssertionError("Invalid Sticker Id: {$id}");
            }
        }
        $this->id = $id;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->channelId,
            $this->type,
            $this->allow,
            $this->deny,
            $this->id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->channelId,
            $this->type,
            $this->allow,
            $this->deny,
            $this->id
        ] = unserialize($data);
    }
}
