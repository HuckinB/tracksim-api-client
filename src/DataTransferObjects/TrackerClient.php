<?php

namespace HuckinB\TrackSimClient\DataTransferObjects;

class TrackerClient
{
    /**
     * + Does the user have the client installed.
     *
     * @var bool $installed
     */
    protected bool $installed;

    /**
     * The client version.
     *
     * @var ClientVersion $version
     */
    protected ClientVersion $version;

    /**
     * Create a new TrackerClient Instance
     */
    public function __construct($data)
    {
        $this->installed = $data->is_installed;
        $this->version = new ClientVersion($data->version);
    }

    public function isInstalled(): bool
    {
        return $this->installed;
    }

    public function getVersion(): ClientVersion
    {
        return $this->version;
    }
}
