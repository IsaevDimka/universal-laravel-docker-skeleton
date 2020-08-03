<?php
/**
 * @var Goridge\RelayInterface $relay
 */
$debug_start_microtime = microtime(true);

use Spiral\Goridge;
use Spiral\RoadRunner;

ini_set('display_errors', 'stderr');
require 'vendor/autoload.php';

const BY_PASS_PRETTY = 'pretty';

$worker = new RoadRunner\Worker(new Goridge\StreamRelay(STDIN, STDOUT));
$psr7   = new RoadRunner\PSR7Client($worker);

while($req = $psr7->acceptRequest()){
    try{

        $resp = new \Zend\Diactoros\Response();

        $queryParams = $req->getQueryParams();

        $debug = [
            'headers'      => $req->getHeaders(),
            'QueryParams'  => $req->getQueryParams(),
            'uri'          => [
                'sheme' => $req->getUri()->getScheme(),
                'host'  => $req->getUri()->getHost(),
                'path'  => $req->getUri()->getPath(),
                'port'  => $req->getUri()->getPort(),
            ],
            'request_body' => json_decode($req->getBody()->getContents(), 1),
            'duration'     => formatDuration((microtime(true) - $debug_start_microtime)),
        ];

        if($queryParams['pretty']) {
            $json = json_encode($debug, JSON_PRETTY_PRINT);
        }else{
            $json = json_encode($debug);
        }

        $resp->getBody()->write($json);

        $psr7->respond($resp);
    } catch(\Throwable $e){
        $psr7->getWorker()->error((string) $e);
    }
}

/**
 * @param $seconds
 *
 * @return string
 */
function formatDuration($seconds)
{
    if($seconds < 0.001) {
        return round($seconds * 1000000) . 'μs';
    }elseif($seconds < 1){
        return round($seconds * 1000, 2) . 'ms';
    }

    return round($seconds, 2) . 's';
}