<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryptable
{
    /**
     * Get a models attribute on select
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable)) {
            try {
                $value = Crypt::decrypt($value);
            } catch (\Exception $e) {
                return $value;
            }
        }
        return $value;
    }

    /**
     * Set a models attribute on save
     *
     * @param string $key
     * @param string|null $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if (is_null($value)) {
            $value = "";
        }
        if (in_array($key, $this->encryptable) && $value != "") {
            $value = Crypt::encrypt($value);
        }
        return parent::setAttribute($key, $value);
    }
}