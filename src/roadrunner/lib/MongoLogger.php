<?php

declare(strict_types=1);

namespace Lib;

use MongoDB\Client as Mongo;

class MongoLogger
{
    private const MONGO_DSN = 'mongodb://admin:FP8-3hwZN3Qyku_u@zevs-mongodb:27017';

    protected static $instance;

    private Mongo $mongo;

    private $db;

    public function __construct()
    {
        $this->mongo = new Mongo(self::MONGO_DSN);
        $this->db = $this->mongo->laravel_logs;
    }

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function record(array $payload = [], string $collection = 'lead_storage')
    {
        try {
            $collection = $this->db->{$collection};
            return $collection->insertOne($payload);
        } catch (\Throwable $exception) {
            return $exception;
        }
    }
}
