<?php

declare(strict_types=1);

namespace Lib;

use Psr\Http\Message\ServerRequestInterface;

class LeadStorage
{
    public array $payload;

    protected static $instance;

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function accept(ServerRequestInterface $req): void
    {
        $this->payload = [
            'flow_hash' => TDS::generate_hash(10),
            'click_hash' => TDS::generate_hash(10),
            'uuid' => TDS::generate_uuid_v4(),
            'external_api_id' => '',
            'location_iso_code' => '',
            'method' => $_SERVER['REQUEST_METHOD'],
            'referrer' => $req->withCookieParams([
                'CID' => 'click_hash',
            ]),
            'headers' => $req->getHeaders(),
            'QueryParams' => $req->getQueryParams(),
            'request_method' => $req->getMethod(),
            'uri' => [
                'scheme' => $req->getUri()->getScheme(),
                'host' => $req->getUri()->getHost(),
                'path' => $req->getUri()->getPath(),
                'port' => $req->getUri()->getPort(),
            ],
            'request_body' => \json_decode($req->getBody()->getContents(), true),
            'received_at' => date('Y-m-d H:i:s', strtotime('now')),
        ];

        $record = MongoLogger::getInstance()->record($this->payload);

        if ($record->getInsertedId()) {
            $insert_id = (array) $record->getInsertedId();
            $this->payload['external_api_id'] = $insert_id['oid'];
        }
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
