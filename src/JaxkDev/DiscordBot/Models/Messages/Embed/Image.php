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

namespace JaxkDev\DiscordBot\Models\Messages\Embed;

// https://discord.com/developers/docs/resources/channel#embed-object-embed-image-structure
class Image implements \Serializable
{

    /** @var null|string Must be prefixed with `https` */
    private $url;

    /** @var null|int */
    private $width;

    /** @var null|int */
    private $height;

    /** @var null|string */
    private $proxy_icon_url;


    /** Image Constructor.
     * 
     * @param string|null           $url
     * @param int|null              $width
     * @param int|null              $height
     * @param string|null           $proxy_icon_url
     * 
     */
    public function __construct(?string $url = null, ?int $width = null, ?int $height = null, ?string $proxy_icon_url = null)
    {
        $this->setUrl($url);
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setProxyIconUrl($proxy_icon_url);
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        if ($url !== null and !str_starts_with($url, "https")) {
            throw new \AssertionError("URL '$url' must start with https.");
        }
        $this->url = $url;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }
    public function getProxyIconURL(): ?string
    {
        return $this->proxy_icon_url;
    }
    public function setProxyIconUrl(?string $proxy_icon_url): void
    {
        $this->proxy_icon_url = $proxy_icon_url;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->url,
            $this->width,
            $this->height,
            $this->proxy_icon_url
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->url,
            $this->width,
            $this->height,
            $this->proxy_icon_url
        ] = unserialize($data);
    }
}
