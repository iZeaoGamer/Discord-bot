<?php

namespace JaxkDev\DiscordBot\Models\Messages;

use JaxkDev\DiscordBot\Plugin\Utils;


class Stickers implements \Serializable
{

    /** @var string|null */
    protected $id; //null when sending.

    /** @var string|null */
    protected $pack_id; //null if using guild stickers type.

    /** @var string */
    protected $name;

    /** @var string|null */
    protected $description; //null when sending.

    /** @var string|null */
    protected $tags; //null when not using suggestive tags.

    /** @var int */
    protected $type;

    /** @var int */
    protected $format_type;

    /** @var string|null */
    protected $preview;

    /** 
     * Stickers Constructor
     * @param string $name
     * @param int $type
     * @param int $formatType
     * @param string|null $id
     * @param string|null $pack_id
     * @param string|null $description,
     * @param array|null $tags
     * @param string|null $preview_asset
     */
    public function __construct(
        string $name,
        int $type,
        int $formatType,
        ?string $id = null,
        ?string $pack_id = null,
        ?string $description = null,
        ?array $tags = null,
        ?string $preview_asset = null
    ) {
        $this->setName($name);
        $this->setType($type);
        $this->setFormatType($formatType);
        $this->setId($id);
        $this->setPackId($pack_id);
        $this->setDescription($description);
        $this->setTags($tags);
        $this->setPreview($preview_asset);
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
    public function getTags(): ?string{
        return $this->tags;
    }
    public function setTags(?string $tags): void{
    if($tags !== null){
        if(strlen($tags) > 200){
            throw new \AssertionError("The suggestive tags must be below the maximum character limit 200.");
        }
    }
    $this->tags = $tags;
}
public function getPreview(): ?string{
    return $this->preview;
}
public function setPreview(?string $preview): void{
    $this->preview = $preview;
}
 //----- Serialization -----//

 public function serialize(): ?string
 {
     return serialize([
        $this->name,
        $this->type,
        $this->format_type,
        $this->id,
        $this->pack_id,
        $this->description,
        $this->tags,
        $this->preview
     ]);
 }

 public function unserialize($data): void
 {
     [
        $this->name,
        $this->type,
        $this->format_type,
        $this->id,
        $this->pack_id,
        $this->description,
        $this->tags,
        $this->preview,
     ] = unserialize($data);
 }

}


