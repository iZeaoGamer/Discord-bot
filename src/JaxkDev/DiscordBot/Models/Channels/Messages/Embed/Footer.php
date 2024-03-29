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

namespace JaxkDev\DiscordBot\Models\Channels\Messages\Embed;

class Footer implements \Serializable
{

    /** @var null|string 2048 characters */
    private $text;

    /** @var null|string Must be prefixed with `https` */
    private $icon_url;

    /** @var null|string */
    private $proxy_icon_url;


    /** Footer Constructor
     * 
     * @param string|null           $text
     * @param string|null           $icon_url
     * @param string|null           $proxy_icon_url
     * 
     */
    public function __construct(?string $text = null, ?string $icon_url = null, ?string $proxy_icon_url = null)
    {
        $this->setText($text);
        $this->setIconUrl($icon_url);
        $this->setProxyIconUrl($proxy_icon_url);
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        if ($text !== null and strlen($text) > 2048) {
            throw new \AssertionError("Embed footer text can only have up to 2048 characters.");
        }
        $this->text = $text;
    }

    public function getIconUrl(): ?string
    {
        return $this->icon_url;
    }

    public function setIconUrl(?string $icon_url): void
    {
        if ($icon_url !== null and strpos($icon_url, "https") !== 0) {
            throw new \AssertionError("Embed footer icon URL '$icon_url' must start with https.");
        }
        $this->icon_url = $icon_url;
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
            $this->text,
            $this->icon_url,
            $this->proxy_icon_url
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->text,
            $this->icon_url,
            $this->proxy_icon_url
        ] = unserialize($data);
    }
}
