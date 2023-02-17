<?php

namespace HuckinB\TrackSimClient\DataTransferObjects;

class ClientVersion
{
    /**
     * The version of the client.
     *
     * @var string|null $version
     */
    protected ?string $version;

    /**
     * The branch of the client.
     *
     * @var string|null $branch
     */
    protected ?string $branch;

    /**
     * The platform of the client.
     *
     * @var string|null $platform
     */
    protected ?string $platform;

    /**
     * Create a new ClientVersion Instance
     */
    public function __construct($data)
    {
        $this->version = $data->version;
        $this->branch = $data->branch;
        $this->platform = $data->platform;
    }

    public function getVersion(): string
    {
        return $this->version ?? 'Unknown';
    }

    public function getBranch(): string
    {
        return $this->branch ?? 'Unknown';
    }

    public function getPlatform(): string
    {
        return $this->platform ?? 'Unknown';
    }
}