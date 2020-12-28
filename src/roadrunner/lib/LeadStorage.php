<?php

namespace Lib;

use Psr\Http\Message\ServerRequestInterface;
use MongoDB\Client as Mongo;

class LeadStorage
{
    public array $payload;
    public const MONGO_DSN = 'mongodb://admin:FP8-3hwZN3Qyku_u@laravel-mongodb:27017';

    /**
     * @return static
     */
    public static function instance()
    {
        return new static;
    }

    public function accept(ServerRequestInterface $req) : void
    {
        $this->payload = [
            'flow_hash'         => TDS::generate_hash(10),
            'click_hash'        => TDS::generate_hash(10),
            'uuid'              => TDS::generate_uuid_v4(),
            'external_api_id'   => '',
            'location_iso_code' => '',
            'method'            => $_SERVER['REQUEST_METHOD'],
            'referrer'          => $req->withCookieParams(['CID' => 'click_hash']),
            'headers'           => $req->getHeaders(),
            'QueryParams'       => $req->getQueryParams(),
            'request_method'    => $req->getMethod(),
            'uri'               => [
                'scheme' => $req->getUri()->getScheme(),
                'host'   => $req->getUri()->getHost(),
                'path'   => $req->getUri()->getPath(),
                'port'   => $req->getUri()->getPort(),
            ],
            'request_body'      => \json_decode($req->getBody()->getContents(), 1),
            'received_at'       => date("Y-m-d H:i:s", strtotime("now")),
        ];

        // write to mongo
        $mongo      = new Mongo(self::MONGO_DSN);
        $db         = $mongo->laravel_logs;
        $collection = $db->lead_storage;
        $record     = $collection->insertOne((array)$this->payload);

        if ($record->getInsertedId()) {
            $this->payload['external_api_id'] = $record->getInsertedId();
        }
    }

    public function getPayload() : array
    {
        return $this->payload;
    }
}