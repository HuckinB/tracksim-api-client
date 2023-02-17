<?php

namespace HuckinB\TrackSimClient\DataTransferObjects;

class Driver
{
    /**
     * The ID of the driver.
     *
     * @var int
     */
    protected int $id;

    /**
     * The SteamID of the driver.
     *
     * @var int|null
     */
    protected ?int $steamId;

    /**
     * The username of the driver.
     *
     * @var string
     */
    protected string $username;

    /**
     * The url of the driver avatar.
     *
     * @var string
     */
    protected string $avatar;

    /**
     * The client the driver is using.
     *
     * @var TrackerClient $client
     */
    protected TrackerClient $client;

    /**
     * Whether the user is banned from Navio.
     *
     * @var boolean
     */
    protected bool $isBanned;

    /**
     * Create a new Driver Instance
     */
    public function __construct($data) {
        $this->id = $data->id;
        $this->steamId = $data->steam_id;
        $this->username = $data->username;;
        $this->avatar = $data->profile_photo_url;
        $this->client = new TrackerClient($data->client);
        $this->isBanned = $data->is_banned;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSteamId(): int
    {
        return $this->steamId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function isBanned(): bool
    {
        return $this->isBanned;
    }
}
