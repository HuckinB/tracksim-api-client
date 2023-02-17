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
        if(config('tracksim.apikey') !== null) {
            $apikey = config('tracksim.apikey');
        }

        if(!isset($apikey)) {
            throw new \Exception('No API Key provided');
        }

        $base_url = 'https://api.tracksim.app/';
        $version = 'v1';

       $defaultOptions = [
           'base_uri' => $base_url . $version . '/',
           'verify' => false,
           'headers'  => [
               'Authorization' => 'Api-Key ' . $apikey,
               'Accept'        => 'application/json',
               'Content-Type'  => 'application/json',
               'User-Agent'    => 'TrackSim PHP Client v' . \Composer\InstalledVersions::getVersion('huckinb/tracksim-php-client'),
           ]
       ];

       $this->client = new GuzzleClient($defaultOptions);
    }

    /**
     * @throws \Exception
     */
    private function get($endpoint)
    {
        try {
            $response = $this->client->get($endpoint);
        } catch (GuzzleException $e) {
            if ($e->getCode() === 401) {
                throw new \RuntimeException('Invalid API Key');
            }

            throw $e;
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @throws \Exception
     */
    private function post($endpoint, $data = null)
    {
        $response = $this->client->post($endpoint, [
            'json' => $data
        ]);

        return $response;
    }

    /**
     * @throws \Exception
     */
    private function delete($endpoint, $data = null)
    {
        $response = $this->client->delete($endpoint, [
            'json' => $data
        ]);

        return $response;
    }

    /**
     * This method returns the company details for the company that the API key belongs to.
     *
     * @throws \Exception
     *
     * @return Company
     */
    public function company()
    {
        $response = $this->get('me');

        return new Company($response);
    }

    /**
     * This method will add a driver to the company.
     *
     * @param int|null $steam64
     * @throws \Exception
     */
    public function addDriver(int $steam64 = null)
    {
        if(!isset($steam64)) {
            throw new \Exception('No Steam64 ID provided');
        }

        try {
            $response = $this->post('drivers/add', [
                'steam_id' => $steam64
            ]);
        } catch (GuzzleException $e) {
            throw $e;
        }

        $data = json_decode($response->getBody()->getContents());

        return new Driver($data);
    }

    /**
     * This method will remove a driver from the company.
     *
     * @param int|null $steam64
     * @throws \Exception
     */
    public function removeDriver(int $steam64 = null)
    {
        if(!isset($steam64)) {
            throw new \Exception('No Steam64 ID provided');
        }

        try {
            $response = $this->delete('drivers/remove', [
                'steam_id' => $steam64
            ]);
        } catch (GuzzleException $e) {
            throw $e;
        }

        return $response->getStatusCode() === 200;
    }

    /**
     * This method will return a list of drivers for the company.
     *
     * @throws \Exception
     *
     * @return Driver[]
     */
    public function getDrivers()
    {
        $response = $this->get('drivers');

        $drivers = [];

        foreach ($response as $driver) {
            $drivers[] = new Driver($driver);
        }

        return $drivers;
    }
}