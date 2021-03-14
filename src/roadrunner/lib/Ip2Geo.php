<?php

declare(strict_types=1);

namespace Lib;

use GeoIp2\Database\Reader as Reader;
use GeoIp2\Model\City;

class Ip2Geo
{
    public const DB_PATH = __DIR__ . '/../db/geoip.mmdb';

    public ?City $result;

    private Reader $reader;

    public function __construct()
    {
        $this->reader = new Reader(self::DB_PATH);
    }

    public function setIp($ip)
    {
        try {
            $this->result = $this->reader->city($ip);
        } catch (\Throwable $e) {
            $this->result = null;
        }
    }

    public function ipAddress()
    {
        return $this->result->traits->ipAddress;
    }

    public function raw(): array
    {
        return $this->result->jsonSerialize();
    }

    public function getResult(): array
    {
        return [
            'ip_address' => $this->result->traits->ipAddress,
            'country_iso_code' => $this->result->country->isoCode,
            'country' => $this->result->country->name,
            'region' => $this->result->mostSpecificSubdivision->name,
            'postalcode' => $this->result->postal->code,
            'city' => $this->result->city->name,
            'timezone' => $this->result->location->timeZone,
            'continent' => $this->result->continent->code,
            'continent_name' => $this->result->continent->name,
            'latitude' => $this->result->location->latitude,
            'longitude' => $this->result->location->longitude,
        ];
    }

    public function getCountryIsoCode()
    {
        return $this->result ? $this->result->country->isoCode : null;
    }
}
