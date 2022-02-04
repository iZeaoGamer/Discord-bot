<?php

namespace JaxkDev\DiscordBot\Models\Server;

use JaxkDev\DiscordBot\Plugin\Utils;

class Widget implements \Serializable
{

    /** @var string */
    private $server_id;

    /** @var string */
    private $name;

    /** @var int */
    private $presence_count;

    /** @var string|null */
    private $instant_invites;

    /** @var object[] */
    private $channels;

    /** @var object[] */
    private $members;

    /** Widget Model Constructor.
     * 
     * @param string                $server_id
     * @param string                $name
     * @param int                   $presence_count
     * @param string|null           $instant_invites
     * @param object[]              $channels
     * @param object[]              $members
     * 
     */
    public function __construct(
        string $server_id,
        string $name,
        int $presence_count,
        ?string $instant_invites = null,
        array $channels = [],
        array $members = []
    ) {
        $this->setServerId($server_id);
        $this->setName($name);
        $this->setPresenceCount($presence_count);
        $this->setInstantInvites($instant_invites);
        $this->setChannels($channels);
        $this->setMembers($members);
    }
    public function getServerId(): string
    {
        return $this->server_id;
    }
    public function setServerId(string $server_id): void
    {
        if (!Utils::validDiscordSnowflake($server_id)) {
            throw new \AssertionError("Server ID: {$server_id} does not exist!");
        }
        $this->server_id = $server_id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        if (strlen($name) < 2 or strlen($name) > 100) {
            throw new \AssertionError("Name must be between 2-100 characters.");
        }
        $this->name = $name;
    }
    public function getPresenceCount(): int
    {
        return $this->presence_count;
    }
    public function setPresenceCount(int $presence_count): void
    {
        $this->presence_count = $presence_count;
    }
    public function getInstantInvites(): ?string{
        return $this->instant_invites;
    }
    public function setInstantInvites(?string $instant_invites): void{
        $this->instant_invites = $instant_invites;
    }
    /** @return object[] */
    public function getChannels(): array{
        return $this->channels;
    }

    /** @param object[] $channels 
     * 
     * @return void
    */
    public function setChannels(array $channels): void
    {
        $this->channels = $channels;
    }
    
    /** @return object[] */
    public function getMembers(): array{
        return $this->members;
    }

    /** @param object[] $members 
     * 
     * @return void
    */
    public function setMembers(array $members): void
    {
        $this->members = $members;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->server_id,
            $this->name,
            $this->presence_count,
            $this->instant_invites,
            $this->channels,
            $this->members
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->server_id,
            $this->name,
            $this->presence_count,
            $this->instant_invites,
            $this->channels,
            $this->members
        ] = unserialize($data);
    }
}
