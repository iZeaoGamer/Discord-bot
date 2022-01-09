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

namespace JaxkDev\DiscordBot\Models\Interactions\Command;



class Option implements \Serializable

{

    /** @var int */
    private $type;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var bool */
    private $required;

    /** @var Choice[] */
    private $choices;

    /** @var Option[] */
    private $sub_options;

    /** @var int[] */
    private $channelTypes;

    /** @var int */
    private $minValue;

    /** @var int */
    private $maxValue;

    /** @var bool */
    private $autoComplete;


    public const SUB_COMMAND = 1;
    public const SUB_COMMAND_GROUP = 2;
    public const STRING = 3;
    public const INTEGER = 4; // Any integer between -2^53 and 2^53
    public const BOOLEAN = 5;
    public const USER = 6;
    public const CHANNEL = 7; // Includes all channel types + categories
    public const ROLE = 8;
    public const MENTIONABLE = 9; // Includes users and roles
    public const NUMBER = 10; // Any double between -2^53 and 2^53

    /** Command Option Constructor
     * @param int $type
     * @param string $name
     * @param string $description
     * @param bool $required
     * @param Choice[] $choices
     * @param Option[] $sub_options
     * @param int[] $channel_typs
     * @param int $min
     * @param int $max
     * @param bool $autoComplete
     */
    public function __construct(
        int $type,
        string $name,
        string $description,
        bool $required = false,
        array $choices = [],
        array $sub_options = [],
        array $channel_types = [],
        int $min = 0,
        int $max = 1,
        bool $autoComplete = false
    ){
        $this->setType($type);
        $this->setName($name);
        $this->setDescription($description);
        $this->setRequired($required);
        $this->setChoices($choices);
        $this->setSubOptions($sub_options);
        $this->setChannelTypes($channel_types);
        $this->setMinValue($min);
        $this->setMaxValue($max);
        $this->setAutoComplete($autoComplete);
    }
    public function getType(): int{
        return $this->type;
    }
    public function setType(int $type): void{
        if($type < self::SUB_COMMAND or $type > self::NUMBER){
            throw new \AssertionError("Option type: {$type} is invalid. It must be either from 1-10.");
        }
        $this->type = $type;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        if (strlen($description) > 100) {
            throw new \AssertionError("Description: {$description} has a limit of 100 characters. Please reduce it.");
        }
        $this->description = $description;
    }
    public function isRequired(): bool{
        return $this->required;
    }
    public function setRequired(bool $required): void{
        $this->required = $required;
    }

    /** @return Choice[] */
    public function getChoices(): array{
        return $this->choices;
    }

    /** @param Choice[] $choices */
    public function setChoices(array $choices): void{
        $this->choices = $choices;
    }

    /** @return Option[] */
    public function getSubOptions(): array{
        return $this->sub_options;
    }

    /** @param Option[] $sub_options */
    public function setSubOptions(array $sub_options): void{
        $this->sub_options = $sub_options;
    }

    /** @return int[] */
    public function getChannelTypes(): array{
        return $this->channelTypes;
    }

    /** @param int[] $channel_types */
    public function setChannelTypes(array $channel_types): void{
        foreach($channel_types as $type){
            if($type < 0 or $type > 13){
                throw new \AssertionError("Channel Type: {$type} is invalid. It must be between 0-13.");
            }
        }
        $this->channelTypes = $channel_types;
    }
    public function getMinValue(): int{
        return $this->minValue;
    }
    public function setMinValue(int $min): void
    {
        $this->minValue = $min;
    }
    public function getMaxValue(): int{
        return $this->maxValue;
    }
    public function setMaxValue(int $max){
        $this->maxValue = $max;
    }
    public function isAutoComplete(): bool{
        return $this->autoComplete;
    }
    public function setAutoComplete(bool $auto): void
    {
        $this->autoComplete = $auto;
    }

    //----- Serialization -----//

    public function serialize(): ?string
    {
        return serialize([
            $this->type,
            $this->name,
            $this->description,
            $this->required,
            $this->choices,
            $this->sub_options,
            $this->channelTypes,
            $this->minValue,
            $this->maxValue,
            $this->autoComplete
        ]);
    }

    public function unserialize($data): void
    {
        [
            $this->type,
            $this->name,
            $this->description,
            $this->required,
            $this->choices,
            $this->sub_options,
            $this->channelTypes,
            $this->minValue,
            $this->maxValue,
            $this->autoComplete
        ] = unserialize($data);
    }
}
