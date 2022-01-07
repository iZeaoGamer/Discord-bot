<?php

/*
 * This file is a part of the DiscordPHP project.
 *
 * Copyright (c) 2015-present David Cole <david.cole1340@gmail.com>
 *
 * This file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace Discord\Parts\Embed;

use Discord\Parts\Part;

/**
 * A field of an embed object.
 *
 * @property string      $text           Footer text.
 * @property string|null $icon_url       URL of an icon for the footer. Must be https.
 * @property string|null $proxy_icon_url Proxied version of the icon URL.
 */
class Field extends Part
{
    /**
     * @inheritdoc
     */
    protected $fillable = ['name', 'value', 'inline'];

    /**
     * Gets the inline attribute.
     *
     * @return bool The inline attribute.
     */
    protected function getInlineAttribute(): bool
    {
        return (bool) ($this->attributes['inline'] ?? false);
    }
}
