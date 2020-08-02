<?php

namespace App\Console\Commands\Monitor;

use Illuminate\Console\Command;
use Spatie\SslCertificate\Exceptions\CouldNotDownloadCertificate;
use Spatie\SslCertificate\SslCertificate;

class CheckCertificates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:check-certificate
                           {--url= : Only check these urls}';
    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Check the certificates of all sites';

    private $expiration_days;
    private $result = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->expiration_days = config('monitor.check_certificates.expiration_days') ?? 7;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($url = $this->option('url')) {
            return $this->checkCertificate($url);
        }

        $monitors = $this->getForCertificateCheck();
        if (empty($monitors)) {
            return $this->comment('no urls');
        }

        $this->comment('Start checking the certificates of ' . count($monitors) . ' monitors...');

        foreach ($monitors as $monitor) {
            $this->checkCertificate($monitor);
        }

        $this->info('All done!');

    }

    private function getForCertificateCheck()
    {
        return config('monitor.check_certificates.sites');
    }

    private function getCertificateStatusAsEmojiAttribute($certificate_status): string
    {
        if ($certificate_status === true) {
            return '✅';
        }

        if ($certificate_status === false) {
            return '❌';
        }

        return '';
    }

    public function checkCertificate($url)
    {
        $this->result = [
            'domain'  => $url,
            'message' => null,
        ];
        try {
            $certificate = SslCertificate::createForHostName($url);
            $this->result = [
                'url'                       => $url,
                'domain'                    => $certificate->getDomain(),
                'isValidUntil'              => $certificate->isValidUntil(now()->addDays($this->expiration_days)),
                'AdditionalDomains'         => $certificate->getAdditionalDomains(),
                'Issuer'                    => $certificate->getIssuer(),
                'isValid'                   => $certificate->isValid(),
                'validFromDate'             => $certificate->validFromDate()->format('Y-m-d H:i:s'),
                'expirationDate'            => $certificate->expirationDate()->format('Y-m-d H:i:s'),
                'expirationDate_diffInDays' => $certificate->expirationDate()->diffInDays(),
                'SignatureAlgorithm'        => $certificate->getSignatureAlgorithm(),
                'isExpired'                 => $certificate->isExpired(),
            ];
            if ($this->result['isValidUntil'] === false) {
                $this->result['message'] = $this->getCertificateStatusAsEmojiAttribute(false) . " Checking certificate of {$this->result['domain']}: is valid until {$this->expiration_days} days";
                throw new \RuntimeException();
            }
            if ($this->result['isExpired'] === true) {
                $this->result['message'] = $this->getCertificateStatusAsEmojiAttribute(false) . " Checking certificate of {$this->result['domain']}: Certificate is is expired!";
                throw new \RuntimeException($this->result['message']);
            }
            $this->result['message'] = $this->getCertificateStatusAsEmojiAttribute(true) . " Checking certificate of {$this->result['domain']} is valid";
            $this->comment($this->result['message']);
            $this->notify(true);
        } catch (\Throwable $exception) {
            if ($exception instanceof CouldNotDownloadCertificate) {
                $this->result['message'] = $this->getCertificateStatusAsEmojiAttribute(false) . " Checking certificate of {$this->result['domain']}: " . $exception->getMessage();
            }
            $this->error($this->result['message']);
            $this->notify(false);
        }
    }

    private function notify($error = false)
    {
        if ($error) {
            $level = 'info';
        } else {
            $level = 'error';
        }
        logger()->channel('mongodb')->$level($this->result['message'], [
            'collection' => 'MonitorCheckCertificates',
            'domain'     => $this->result['domain'],
            'result'     => $this->result,
        ]);
        logger()->channel('telegram')->$level($this->result['message'], [
            'type'   => 'clear',
            'result' => $this->result,
        ]);
    }

}
