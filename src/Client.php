<?php

namespace HuckinB\TrackSimClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use HuckinB\TrackSimClient\DataTransferObjects\Company;
use HuckinB\TrackSimClient\DataTransferObjects\Driver;

class Client
{
    /**
     * @var Client
     */
    public $client;

    /**
     * @throws \Exception
     */
    public function __construct($apikey = null)
    {
        if (config('tracksim.apikey') !== null) {
            $apikey = config('tracksim.apikey');
        }

        if (!isset($apikey)) {
            throw new Exception('The API key is not set');
        }

        $base_url = 'https://api.tracksim.app/';
        $version = 'v1';

        $defaultOptions = [
            'base_uri' => $base_url . $version . '/',
            'verify' => false,
            'headers' => [
                'Authorization' => 'Api-Key ' . $apikey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'TrackSim PHP Client v' . \Composer\InstalledVersions::getVersion('huckinb/tracksim-php-client'),
            ]
        ];

        $this->client = new GuzzleClient($defaultOptions);
    }

    /**
     * This method returns the company details for the company that the API key belongs to.
     *
     * @return Company
     * @throws Exception|GuzzleException
     *
     */
    public function company(): Company
    {
        $response = $this->get('me');

        if ($response->getStatusCode() !== 200) {
            throw new Exception('Unable to get company details');
        }

        $response = json_decode($response->getBody()->getContents(), false);

        return new Company($response);
    }

    /**
     * @throws GuzzleException
     */
    private function get($endpoint)
    {
        return $this->client->get($endpoint);
    }

    /**
     * This method will add a driver to the company.
     *
     * @param int $steam64
     * @return Driver
     * @throws Exception
     */
    public function addDriver(int $steam64): Driver
    {
        if (!isset($steam64)) {
            throw new Exception('No Steam64 ID provided');
        }

        try {
            $response = $this->post('drivers/add', [
                'steam_id' => $steam64
            ]);
        } catch (GuzzleException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents());

            switch ($response->error) {
                case 'steam_profile_private':
                    throw new Exception('The requested steam user profile is private');
                    break;
                case 'resource_already_exists':
                    throw new Exception('The selected driver is already connected to your company');
                    break;
                case 'driver_no_compatible_games':
                    throw new Exception('The requested steam user doesn\'t own ETS2 or ATS (This could be due to it being private on their profile)');
                    break;
                case 'max_driver_limit_reached ':
                    throw new Exception('The maximum amount of drivers has been reached');
                    break;
                default:
                    throw new Exception($e->getMessage());
                    break;
            }
        }

        $response = json_decode($response->getBody()->getContents(), false);

        return new Driver($response);
    }

    /**
     * @throws GuzzleException
     */
    private function post($endpoint, $data = null)
    {
        return $this->client->post($endpoint, [
            'json' => $data
        ]);
    }

    /**
     * This method will remove a driver from the company.
     *
     * @param int $steam64
     * @return bool
     * @throws Exception|GuzzleException
     */
    public function removeDriver(int $steam64): bool
    {
        if (!isset($steam64)) {
            throw new Exception('No Steam64 ID provided');
        }

        try {
            $response = $this->delete('drivers/remove', [
                'steam_id' => $steam64
            ]);
        } catch (GuzzleException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), false);

            if ($response->error === 'resource_not_found') {
                throw new Exception('The selected driver is not connected to your company');
            }

            throw new Exception($e->getMessage());
        }

        return $response->getStatusCode() === 200;
    }

    /**
     * @throws GuzzleException
     */
    private function delete($endpoint, $data = null)
    {
        return $this->client->delete($endpoint, [
            'json' => $data
        ]);
    }

    /**
     * This method will return a driver from the company.
     *
     * @param $steam64
     * @return Driver
     * @throws Exception
     */
    public function getDriver($steam64): Driver
    {
        if (!isset($steam64)) {
            throw new Exception('No Steam64 ID provided');
        }

        try {
            $response = $this->get('drivers/' . $steam64 . '/details');
        } catch (GuzzleException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), false);

            if ($response->error === 'driver_does_not_exist_in_company') {
                throw new Exception('The selected driver is not connected to your company');
            }

            throw new Exception($e->getMessage());
        }

        $response = json_decode($response->getBody()->getContents(), false);

        return new Driver($response);
    }

    /**
     * This method will allow you to update a driver's settings.
     *
     * @param $steam64
     * @param array $data
     *
     * @return Driver
     *
     * @throws Exception
     */
    public function updateDriver($steam64, array $data): Driver
    {
        if (!isset($steam64)) {
            throw new Exception('No Steam64 ID provided');
        }

        $allowedFields = [
            'eut2_job_logging',
            'eut2_live_tracking',
            'ats_job_logging',
            'ats_live_tracking',
        ];

        foreach ($allowedFields as $field) {
            if (!isset($data[$field])) {
                throw new Exception('Missing field: ' . $field);
            }

            if (!is_bool($data[$field])) {
                throw new Exception('Field ' . $field . ' must be a boolean');
            }
        }

        try {
            $response = $this->patch('drivers/' . $steam64 . '/manage', $data);
        } catch (GuzzleException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), false);

            if (is_object($response->error)) {
                $message = '';
                foreach ($response->error as $errors) {
                    foreach ($errors as $error) {
                        $message .= $error . ' ';
                    }
                }

                throw new Exception($message);
            }

            if ($response->error === 'driver_does_not_exist_in_company') {
                throw new Exception('The selected driver is not connected to your company');
            }

            throw new Exception($e->getMessage());
        }

        $response = json_decode($response->getBody()->getContents(), false);

        return new Driver($response);
    }

    /**
     * This method will return a true or false if the driver is in the company.
     *
     * @param $steam64
     *
     * @return bool
     */
    public function driverExists($steam64): bool
    {
        if (!isset($steam64)) {
            throw new Exception('No Steam64 ID provided');
        }

        try {
            $response = $this->get('drivers/' . $steam64 . '/details');
        } catch (GuzzleException $e) {
            $response = json_decode($e->getResponse()->getBody()->getContents(), false);

            if ($response->error === 'driver_does_not_exist_in_company') {
                return false;
            }

            throw new Exception($e->getMessage());
        }

        return true;
    }

    /**
     * @throws GuzzleException
     */
    private function patch($endpoint, $data = null)
    {
        return $this->client->patch($endpoint, [
            'json' => $data
        ]);
    }
}