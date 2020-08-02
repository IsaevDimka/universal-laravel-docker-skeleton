<?php


namespace App\Traits;


trait PhoneNumberFormattingTrait
{
    /**
     * @param $phone
     * @param $country_iso_code
     *
     * @return array
     */
    public function phoneNumberFormatting($phone, $country_iso_code)
    {
        $cleanup_phonenumber = $phone;

        /**
         * Formatting phone number
         */
        try {
            /**
             * Cleanup phone number
             */
            if (!empty($phone))
            {
                $cleanup_phonenumber = preg_replace('![^\w\d\x\s]*!', '', $phone);               # clear special symbols
                $cleanup_phonenumber = preg_replace("/[^0-9\s]/", "", $cleanup_phonenumber);     # clear number
                $cleanup_phonenumber = str_replace(' ', '', $cleanup_phonenumber);               # clear spaces
            }else{
                throw new \RuntimeException('Phone number is required', 400);
            }

            if (empty($country_iso_code))
            {
                throw new \RuntimeException('Country iso code is required', 400);
            }

            $parsePhone = \Propaganistas\LaravelPhone\PhoneNumber::make($cleanup_phonenumber, $country_iso_code);

            $status = true;
            $message = 'Success';
            $formatNational = $parsePhone->formatNational();
            $formatInternational = $parsePhone->formatInternational();
            $formatE164 = $parsePhone->formatE164();
            $formatRFC3966 = $parsePhone->formatRFC3966();

//            $formatInternational = preg_replace('/\s+/', '', $formatInternational); # удаляем пробелы
//            $formatInternational = str_replace(['-'], '', $formatInternational);
//            $formatNational = preg_replace('/\s+/', '', $formatNational); # удаляем пробелы
        } catch (\Throwable $exception) {
            $status = false;
            $message = (string)$exception->getMessage() . ' ' . $country_iso_code;
            $formatNational = '';
            $formatInternational = '';
            $formatE164 = '';
            $formatRFC3966 = '';
        }

        return [
            'payload'   => [
                compact(
                    'phone',
                    'country_iso_code',
                )
            ],
            'status'    => $status,
            'message'   => $message,
            'cleanup'   => $cleanup_phonenumber,
            'formatted' => compact(
                'formatNational',
                'formatInternational',
                'formatRFC3966',
                'formatE164',
            ),
        ];
    }
}
