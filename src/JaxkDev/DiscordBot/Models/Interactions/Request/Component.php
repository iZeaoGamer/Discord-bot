<?php

namespace JaxkDev\DiscordBot\Models\Interactions\Request;

use JaxkDev\DiscordBot\Models\Server\Emoji;

class Component implements \Serializable
{

    /** @var int */
    private $type;

    /** @var string|null */
    private $custom_id; //null when not using buttons and select menus.

    /** @var bool|null */
    private $disabled; //null when not using buttons and select menus.

    /** @var int|null */
    private $style; //null when not using buttons.

    /** @var string|null */
    private $label; //null when not using buttons.

    /** @var Emoji|null */
    private $emoji; //null when not using buttons.

    /** @var string|null */
    private $url; //null when not using buttons with link style type.

    /** @var object[]|null */
    private $options; //null when not using select menus.

    /** @var string|null */
    private $placeholder; //null when not using select menus and text inputs.

    /** @var int|null */
    private $min_values; //null when not using select menus.

    /** @var int|null */
    private $max_values; //null when not using select menus.

    /** @var Component[]|null */
    private $components; //null when not using Action Row Components.

    /** @var int|null */
    private $min_length; //null when not using text inputs.

    /** @var int|null */
    private $max_length; //null when not using text inputs.

    /** @var bool|null */
    private $required; //null when not using text inputs.

    /** @var string|null */
    private $value; //null when not using text inputs.

    /** Component Constructor
     * 
     * @param int                       $type
     * @param string|null               $custom_id
     * @param bool|null                 $disabled
     * @param int|null                  $style
     * @param string|null               $label
     * @param Emoji|null                $emoji
     * @param string|null               $url
     * @param object[]|null             $options
     * @param string|null               $placeholder
     * @param int|null                  $min_values
     * @param int|null                  $max_values
     * @param Component[]|null          $components
     * @param int|null                  $min_length
     * @param int|null                  $max_length
     * @param bool|null                 $required
     * @param string|null               $value
     */
    public function __construct(
        int $type,
        ?string $custom_id = null,
        ?bool $disabled = null,
        ?int $style = null,
        ?string $label = null,
        ?Emoji $emoji = null,
        ?string $url = null,
        ?array $options = null,
        ?string $placeholder = null,
        ?int $min_values = null,
        ?int $max_values = null,
        ?array $components = null,
        ?int $min_length = null,
        ?int $max_length = null,
        ?bool $required = null,
        ?string $value = null
    ) {
        $this->setType($type);
        $this->setCustomId($custom_id);
        $this->setDisabled($disabled);
        $this->setStyle($style);
        $this->setLabel($label);
        $this->setEmoji($emoji);
        $this->setUrl($url);
        $this->setOptions($options);
        $this->setPlaceHolder($placeholder);
        $this->setMinValues($min_values);
        $this->setMaxValues($max_values);
        $this->setComponents($components);
        $this->setMinLength($min_length);
        $this->setMaxLength($max_length);
        $this->setRequired($required);
        $this->setValue($value);
    }
    public function getType(): int
    {
        return $this->type;
    }
    public function setType(int $type)
    {
        $this->type = $type;
    }
    public function getCustomId(): ?string
    {
        return $this->custom_id;
    }
    public function setCustomId(?string $custom_id)
    {
        if ($custom_id !== null and strlen($custom_id) > 100) {
            throw new \AssertionError("Custom ID must be below 100 characters.");
        }
        $this->custom_id = $custom_id;
    }
    public function isDisabled(): bool
    {
        return $this->disabled ?? false;
    }
    public function setDisabled(?bool $disabled): void
    {
        $this->disabled = $disabled;
    }
    public function getStyle(): ?int
    {
        return $this->style;
    }
    public function setStyle(?int $style): void
    {
        $this->style = $style;
    }
    public function getLabel(): ?string
    {
        return $this->label;
    }
    public function setLabel(?string $label): void
    {
        if ($label !== null and strlen($label) > 80) {
            throw new \AssertionError("Label must be below 80 characters.");
        }
        $this->label = $label;
    }
    public function getEmoji(): ?Emoji
    {
        return $this->emoji;
    }
    public function setEmoji(?Emoji $emoji): void
    {
        $this->emoji = $emoji;
    }
    public function getURL(): ?string
    {
        return $this->url;
    }
    public function setURL(?string $url): void
    {
        $this->url = $url;
    }

    /** @return object[]|null */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /** @param object[]|null $options */
    public function setOptions(?array $options): void
    {
        $this->options = $options;
    }
    public function getPlaceHolder(): ?string
    {
        return $this->placeholder;
    }
    public function setPlaceHolder(?string $placeholder): void
    {
        if ($placeholder !== null and strlen($placeholder) > 100) {
            throw new \AssertionError("PlaceHolder must be below 100 characters.");
        }
        $this->placeholder = $placeholder;
    }
    public function getMinValues(): ?int
    {
        return $this->min_values;
    }
    public function setMinValues(?int $min_values): void
    {
        $this->min_values = $min_values;
    }
    public function getMaxValues(): ?int
    {
        return $this->max_values;
    }
    public function setMaxValues(?int $max_values): void
    {
        $this->max_values = $max_values;
    }

    /** @return Component[]|null */
    public function getComponents(): ?array
    {
        return $this->components;
    }

    /** @param Component[]|null $components */
    public function setComponents(?array $components): void
    {
        $this->components = $components;
    }

    public function getMinLength(): ?int
    {
        return $this->min_length;
    }
    public function setMinLength(?int $min_length): void
    {
        $this->min_length = $min_length;
    }

    public function getMaxLength(): ?int
    {
        return $this->max_length;
    }
    public function setMaxLength(?int $max_length): void
    {
        $this->max_values = $max_length;
    }
    public function isRequired(): bool
    {
        return $this->required ?? false;
    }
    public function setRequired(?bool $required): void
    {
        $this->required = $required;
    }
    public function getValue(): ?string
    {
        return $this->value;
    }
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->type,
            $this->custom_id,
            $this->disabled,
            $this->style,
            $this->label,
            $this->emoji,
            $this->url,
            $this->options,
            $this->placeholder,
            $this->min_values,
            $this->max_values,
            $this->components,
            $this->min_length,
            $this->max_length,
            $this->required,
            $this->value
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->type,
            $this->custom_id,
            $this->disabled,
            $this->style,
            $this->label,
            $this->emoji,
            $this->url,
            $this->options,
            $this->placeholder,
            $this->min_values,
            $this->max_values,
            $this->components,
            $this->min_length,
            $this->max_length,
            $this->required,
            $this->value
        ] = unserialize($data);
    }
}
