<?php

namespace Lib;

class TDS
{

    public function __construct()
    {
    }

    /**
     * Generate a unique UUID v4
     *
     * @return string
     */
    public static function generate_uuid_v4() : string
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    /**
     * Generate a unique hash string
     *
     * @param int $length
     *
     * @return string
     */
    public static function generate_hash($length = 16) : string
    {
        do {
            $string = '';
            while (($len = strlen($string)) < $length) {
                $size = $length - $len;
                $bytes = random_bytes($size);
                $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
            }
            return $string;
        } while (true);
    }

    public static function getKeyByWeights(array $weights = []): array
    {
        $rand = mt_rand(0, 1000);
        foreach($weights as $key => $weight){
            $realWeight = $weight * 10;
            if($rand >= 0 && $rand <= $realWeight) {
                return [
                    'node'       => $key,
                    'rand'       => $rand,
                    'weight'     => $weight,
                    'realWeight' => $realWeight,
                ];
            }
            $rand -= $realWeight;
        }
    }
}