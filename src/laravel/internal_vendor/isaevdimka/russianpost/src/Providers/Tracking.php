<?php

declare(strict_types=1);

namespace IsaevDimka\RussianPost\Providers;

use IsaevDimka\RussianPost\Exceptions\StatusValidationException;
use IsaevDimka\RussianPost\Exceptions\TrackingException;
use IsaevDimka\RussianPost\StatusList;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Tracking implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const NAMESPACE_HISTORY = 'http://russianpost.org/operationhistory';

    public const NAMESPACE_DATA = 'http://russianpost.org/operationhistory/data';

    public const NAMESPACE_DATA1 = 'http://www.russianpost.org/RTM/DataExchangeESPP/Data';

    /**
     * @var \SoapClient
     */
    public $client = false;

    private $wsdl = 'https://tracking.pochta.ru';

    private $login = '';

    private $password = '';

    private $service = '';

    private $timeout = 60;

    /**
     * Tracking constructor.
     *
     * @param $service
     * @param $config
     * @param int $timeout
     * @throws \SoapFault
     */
    public function __construct($service, $config, $timeout = 60)
    {
        $this->createClient($service);
        $this->login = $config['auth']['tracking']['login'];
        $this->password = $config['auth']['tracking']['password'];
        $this->service = $service;
        $this->timeout = $timeout;
    }

    /**
     * Получение подробной информации обо всех операциях, совершенных над отправлением
     * @param $rpo - ШК отправления
     * @param string $lang - Язык названия операций (RUS, ENG)
     * @return \stdClass[]
     * @throws \SoapFault
     */
    public function getOperationsByRpo($rpo, $lang = 'RUS')
    {
        // Если пакетный клиент, меняем на штучный
        if ($this->service == 'pack') {
            $this->createClient('single');
        }

        $requestParams = new \SoapVar([
            new \SoapVar([
                new \SoapVar($rpo, XSD_STRING, null, null, 'Barcode', self::NAMESPACE_DATA),
                new \SoapVar(0, XSD_INT, null, null, 'MessageType', self::NAMESPACE_DATA),
                new \SoapVar($lang, XSD_STRING, null, null, 'Language', self::NAMESPACE_DATA),
            ], SOAP_ENC_OBJECT, null, null, 'OperationHistoryRequest', self::NAMESPACE_DATA),
            new \SoapVar([
                new \SoapVar($this->login, XSD_STRING, null, null, 'login', self::NAMESPACE_DATA),
                new \SoapVar($this->password, XSD_STRING, null, null, 'password', self::NAMESPACE_DATA),
            ], SOAP_ENC_OBJECT, null, null, 'AuthorizationHeader', self::NAMESPACE_DATA),
        ], SOAP_ENC_OBJECT);

        $response = $this->client->getOperationHistory($requestParams);

        if ($this->logger) {
            $this->logger->info("Russian Post Tracking API request: \n\r" . $this->client->__getLastRequest());
            $this->logger->info("Russian Post Tracking API response: \n\r" . $this->client->__getLastResponse());
        }

        $result = $response->OperationHistoryData;

        if (! isset($result->historyRecord)) {
            return [];
        }

        if (! is_array($result->historyRecord)) {
            $result->historyRecord = [$result->historyRecord];
        }

        return ! empty($result->historyRecord) ? $result->historyRecord : [];
    }

    /**
     * Получение информации об операциях с наложенным платежом, который связан с почтовым отправлением.
     * @param $rpo - ШК отправления
     * @param string $lang - Язык названия операций (RUS, ENG)
     * @return \stdClass
     * @throws \SoapFault
     */
    public function getNpayInfo($rpo, $lang = 'RUS')
    {
        // Если пакетный клиент, меняем на штучный
        if ($this->service == 'pack') {
            $this->createClient('single');
        }

        $requestParams = new \SoapVar([
            new \SoapVar([
                new \SoapVar($this->login, XSD_STRING, null, null, 'login', self::NAMESPACE_DATA),
                new \SoapVar($this->password, XSD_STRING, null, null, 'password', self::NAMESPACE_DATA),
            ], SOAP_ENC_OBJECT, null, null, 'AuthorizationHeader', self::NAMESPACE_DATA),
            new \SoapVar(
                '<ns2:PostalOrderEventsForMailInput Barcode="' . $rpo . '" Language="' . $lang . '" />',
                XSD_ANYXML,
                null,
                null,
                'PostalOrderEventsForMailInput',
                self::NAMESPACE_DATA1
            ),
        ], SOAP_ENC_OBJECT);

        $response = $this->client->PostalOrderEventsForMail($requestParams);

        if ($this->logger) {
            $this->logger->info("Russian Post Tracking API request: \n\r" . $this->client->__getLastRequest());
            $this->logger->info("Russian Post Tracking API response: \n\r" . $this->client->__getLastResponse());
        }

        $result = $response->PostalOrderEventsForMaiOutput;

        if (! empty($result->PostalOrderEvent) && ! is_array($result->PostalOrderEvent)) {
            $result->PostalOrderEvent = [$result->PostalOrderEvent];
        }

        return ! empty($result->PostalOrderEvent) ? $result->PostalOrderEvent : [];
    }

    /**
     * Создание запроса на получение информации о операциях с переданными отправлениями
     * @param $rpoList - массиш ШК отправлений
     * @param string $lang - Язык названия операций (RUS, ENG)
     * @return array
     * @throws \SoapFault
     */
    public function getTickets($rpoList, $lang = 'RUS')
    {
        // Если штучный клиент, меняем на пакетный
        if ($this->service == 'single') {
            $this->createClient('pack');
        }

        // Бьем по 500, если больше, то ловис HTTP Exception так как слишком большой размер ответа от ПРФ
        $rpoPack = array_chunk($rpoList, 500);
        $requestParams = new \stdClass();
        $requestParams->login = $this->login;
        $requestParams->password = $this->password;
        $requestParams->language = $lang;
        $requestParams->request = new \stdClass();

        $result['tickets'] = $result['not_create'] = [];

        foreach ($rpoPack as $rpoList) {
            $requestParams->Item = [];

            foreach ($rpoList as $rpo) {
                $item = new \stdClass();
                $item->Barcode = $rpo;
                $requestParams->request->Item[] = $item;
            }

            $response = $this->client->getTicket($requestParams);

            if ($this->logger) {
                $this->logger->info("Russian Post Tracking API request: \n\r" . $this->client->__getLastRequest());
                $this->logger->info("Russian Post Tracking API response: \n\r" . $this->client->__getLastResponse());
            }

            if (! empty($response) && ! empty($response->value)) {
                $result['tickets'][] = $response->value;
            } else {
                $result['not_create'] = array_merge($result['not_create'], $rpoList);
            }
        }

        return $result;
    }

    /**
     * Получение подробной информации обо всех операциях, совершенных над переданными отправлениями в тикете
     * @param $ticket
     * @return array|\stdClass
     * @throws TrackingException
     * @throws \SoapFault
     */
    public function getOperationsByTicket($ticket)
    {
        // Если штучный клиент, меняем на пакетный
        if ($this->service == 'single') {
            $this->createClient('pack');
        }

        $statusList = new StatusList();

        $requestParams = new \stdClass();
        $requestParams->login = $this->login;
        $requestParams->password = $this->password;
        $requestParams->ticket = $ticket;

        $response = $this->client->getResponseByTicket($requestParams);

        if ($this->logger) {
            $this->logger->info("Russian Post Tracking API request: \n\r" . $this->client->__getLastRequest());
            $this->logger->info("Russian Post Tracking API response: \n\r" . $this->client->__getLastResponse());
        }

        if (! empty($response->error) || empty($response->value)) {
            throw new TrackingException('Ответ по тикету ' . $ticket . ' еще не готов.');
        }

        /** @var \stdClass $result */
        $result = ! is_array($response->value->Item) ? [$response->value->Item] : $response->value->Item;

        // Проставляем название подстатуса из справочника
        foreach ($result as $key => &$item) {
            if (empty($item->Operation)) {
                continue;
            }

            $rpo = (string) $item->Barcode;
            if (! is_array($item->Operation)) {
                $item = [$item->Operation];
            } else {
                $item = $item->Operation;
            }

            foreach ($item as &$operation) {
                try {
                    $statusInfo = $statusList->getInfo($operation->OperTypeID, $operation->OperCtgID);
                    $operation->OperCtgName = $statusInfo['substatusName'];
                    $operation->isFinal = $statusInfo['isFinal'];
                } catch (StatusValidationException $e) {
                    $operation->OperCtgName = $e->getMessage();
                    $operation->isFinal = false;
                }
            }

            $result[$rpo] = $item;
            unset($result[$key]);
        }

        return $result;
    }

    /**
     * @param $service
     * @throws \SoapFault
     */
    private function createClient($service)
    {
        $this->service = $service;

        if ($service != 'pack') {
            $wsdl = $this->wsdl . '/tracking-web-static/rtm34_wsdl.xml';
            $soapVersion = SOAP_1_2;
        } else {
            $wsdl = $this->wsdl . '/tracking-web-static/fc_wsdl.xml';
            $soapVersion = SOAP_1_1;
        }

        $this->client = new \SoapClient(
            $wsdl,
            [
                'trace' => 1,
                'soap_version' => $soapVersion,
                'use' => SOAP_LITERAL,
                'style' => SOAP_DOCUMENT,
                'connection_timeout' => $this->timeout,
            ]
        );
    }
}
