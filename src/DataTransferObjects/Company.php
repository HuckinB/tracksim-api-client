<?php

namespace HuckinB\TrackSimClient\DataTransferObjects;

class Company
{
    /**
     * The company's logo.
     *
     * @var int
     */
    protected int $id;

/**
     * The company's name.
     *
     * @var string
     */
    protected string $name;

    /**
     * The company's logo.
     *
     * @var string|null
     */
    protected ?string $logo;

    /**
     * The Discord Application ID's for Rich Presence.
     *
     * @var DiscordRichPresence
     */
    protected DiscordRichPresence $richPresence;

    /**
     * Create a new Company Instance
     */
    public function __construct($data)
    {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->logo = $data->logo_url;
        $this->richPresence = new DiscordRichPresence($data->discord_rpc);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function getRichPresence(): DiscordRichPresence
    {
        return $this->richPresence;
    }
}