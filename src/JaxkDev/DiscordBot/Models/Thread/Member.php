<?php 
namespace JaxkDev\DiscordBot\Models\Thread;

use JaxkDev\DiscordBot\Plugin\Utils;
class Member implements \Serializable{

    /** @var string|null */
    private $id; //null when creating.

    /** @var string|null */
    private $user_id; //null when creating.
    
    /** @var int */
    private $join_timestamp;

    /** @var int */
    private $flags;

    /** Thread Member Constructor
     * 
     * @param int                   $join_timestamp
     * @param int                   $flags
     * @param string|null           $id
     * @param string|null           $user_id
     * 
     */
    public function __construct(int $join_timestamp, int $flags, ?string $id = null, ?string $user_id = null)
    {
        $this->setJoinTimestamp($join_timestamp);
        $this->setFlags($flags);
        $this->setId($id);
        $this->setUserId($user_id);
    }

    public function getJoinedTimestamp(): int{
        return $this->join_timestamp;
    }
    public function setJoinTimestamp(int $join_timestamp): void{
        $this->join_timestamp = $join_timestamp;
    }
    public function getFlags(): int{
        return $this->flags;
    }
    public function setFlags(int $flags): void{
        $this->flags = $flags;
    }
    public function getId(): ?string{
        return $this->id;
    }
    public function setId(?string $id): void{
        if($id !== null and !Utils::validDiscordSnowflake($id)){
            throw new \AssertionError("ID: {$id} is invalid.");
        }
        $this->id = $id;
    }
    public function getUserId(): ?string{
        return $this->user_id;
    }
    public function setUserId(?string $user_id): void{
        if($user_id !== null and !Utils::validDiscordSnowflake($user_id)){
            throw new \AssertionError("User ID: {$user_id} is invalid.");
        }
        $this->user_id = $user_id;
    }
//----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->join_timestamp,
            $this->flags,
            $this->id,
            $this->user_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->join_timestamp,
            $this->flags,
            $this->id,
            $this->user_id
        ] = unserialize($data);
    }
}