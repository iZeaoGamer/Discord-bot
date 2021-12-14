<?php

namespace JaxkDev\DiscordBot\Models\Messages;

use JaxkDev\DiscordBot\Plugin\Utils;

use JaxkDev\DiscordBot\Models\User;

class Stickers implements \Serializable
{


    /** @var string */
    protected $name;

    /** @var string|null */
    protected $description; //null when sending.

    /** @var int */
    protected $type;

    /** @var int */
    protected $format_type;

    /** @var bool|null */
    protected $available; //null when creating.

    /** @var string|null */
    protected $serverId;

    /** @var User|null */
    protected $user;

    /** @var int|null */
    protected $sortValue;

    /** @var array */
    protected $tags; //null when not using suggestive tags.

    /** @var string|null */
    protected $id; //null when sending.

    /** @var string|null */
    protected $pack_id; //null if using guild stickers type.

    public function __construct(string $name, string $description, int $type, int $format_type, ?bool $available = null, ?string $server_id = null, ?User $user = null, ?int $sort_value = null, array $tags = [], ?string $id = null, ?string $pack_id = null)
    {
        $this->setName($name);
        $this->setDescription($description);
        $this->setType($type);
        $this->setFormatType($format_type);
        $this->setAvailable($available);
        $this->setServerId($server_id);
        $this->setUser($user);
        $this->setSortValue($sort_value);
        $this->setTags($tags);
        $this->setId($id);
        $this->setPackId($pack_id);
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
        if ($type < 1 or $type > 2) {
            throw new \AssertionError("Invalid Sticker type. Must only be Standard (1) or Guild (2).");
        }
        $this->type = $type;
    }
    public function getFormatType(): int
    {
        return $this->format_type;
    }
    public function setFormatType(int $format_type): void
    {
        if ($format_type < 1 or $format_type > 3) {
            throw new \AssertionError("Invalid Sticker type. Must only be PNG (1), APNG (2), or LOTTIE (3).");
        }
        $this->format_type = $format_type;
    }
    public function isAvailable(): ?bool
    {
        return $this->available;
    }
    public function setAvailable(?bool $available)
    {
        $this->available = $available;
    }
    public function getServerId(): ?string
    {
        return $this->serverId;
    }
    public function setServerId(?string $serverId)
    {
        $this->serverId = $serverId;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user)
    {
        $this->user = $user;
    }
    public function getSortValue(): ?int
    {
        return $this->sortValue;
    }
    public function setSortValue(?int $sortValue)
    {
        $this->sortValue = $sortValue;
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
    public function getPackId(): ?string
    {
        return $this->pack_id;
    }
    public function setPackId(?string $pack_id): void
    {
        if ($pack_id !== null) {
            if (!Utils::validDiscordSnowflake($pack_id)) {
                throw new \AssertionError("Invalid Sticker Id: {$pack_id}");
            }
        }
        $this->pack_id = $pack_id;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): void
    {
        if ($description !== null) {
            if (strlen($description) > 100) {
                throw new \AssertionError("The Sticker Description must be below 100 characters.");
            }
        }
        $this->description = $description;
    }
    public function getTags(): array
    {
        return $this->tags;
    }
    /** @param string[] $tags
     * @return void
     */
    public function setTags(array $tags): void
    {
        $limit = 0;
        foreach ($tags as $tag) {
            $limit += 1;
        }
        if ($limit > 200) {
            throw new \AssertionError("The tag {$tag} must be below the 200 characters limit.");
        }
        $this->tags = $tags;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->name,
            $this->description,
            $this->type,
            $this->format_type,
            $this->available,
            $this->serverId,
            $this->user,
            $this->sortValue,
            $this->tags,
            $this->id,
            $this->pack_id
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->name,
            $this->description,
            $this->type,
            $this->format_type,
            $this->available,
            $this->serverId,
            $this->user,
            $this->sortValue,
            $this->tags,
            $this->id,
            $this->pack_id
        ] = unserialize($data);
    }
}
