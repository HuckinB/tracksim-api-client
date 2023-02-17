<?php

namespace HuckinB\TrackSimClient\DataTransferObjects;

class DiscordRichPresence
{
    /**
     * The Application ID for ETS2.
     *
     * @var int|null
     */
    protected ?int $ets2;

    /**
     * The Application ID for ATS.
     *
     * @var int|null
     */
    protected ?int $ats;

    /**
     * Create a new DiscordRichPresence Instance
     */
    public function __construct($data)
    {
        $this->ets2 = $data->eut2_app_id;
        $this->ats = $data->ats_app_id;
    }

    public function getEts2(): ?int
    {
        return $this->ets2;
    }

    public function getAts(): ?int
    {
        return $this->ats;
    }
}