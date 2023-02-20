<?php

namespace HuckinB\TrackSimClient\DataTransferObjects;

class DriverSettings
{
    /**
     * Whether the driver has job logging enabled for ETS2.
     *
     * @var bool
     */
    protected bool $eut2_job_logging;

    /**
     * Whether the driver has live tracking enabled for ETS2.
     *
     * @var bool
     */
    protected bool $eut2_live_tracking;

    /**
     * Whether the driver has job logging enabled for ATS.
     *
     * @var bool
     */
    protected bool $ats_job_logging;

    /**
     * Whether the driver has live tracking enabled for ATS.
     *
     * @var bool
     */
    protected bool $ats_live_tracking;

    /**
     * Create a new Driver Settings Instance
     */
    public function __construct($data)
    {
        $this->eut2_job_logging = $data->eut2->job_logging;
        $this->eut2_live_tracking = $data->eut2->live_tracking;
        $this->ats_job_logging = $data->ats->job_logging;
        $this->ats_live_tracking = $data->ats->live_tracking;
    }

    /**
     * Get the value of whether the driver has job logging enabled for ETS2.
     *
     * @return bool
     */
    public function etsJobLoggingEnabled()
    {
        return $this->eut2_job_logging;
    }

    /**
     * Get the value of whether the driver has live tracking enabled for ETS2.
     *
     * @return bool
     */
    public function etsLiveTrackingEnabled()
    {
        return $this->eut2_live_tracking;
    }

    /**
     * Get the value of whether the driver has job logging enabled for ATS.
     *
     * @return bool
     */
    public function atsJobLoggingEnabled()
    {
        return $this->ats_job_logging;
    }

    /**
     * Get the value of whether the driver has live tracking enabled for ATS.
     *
     * @return bool
     */
    public function atsLiveTrackingEnabled()
    {
        return $this->ats_live_tracking;
    }

}
