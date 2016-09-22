<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;

class Transport {
    const LIVE_PRICING = '/apiservices/pricing/v1.0';
//    const LIVE_PRICING = '/xep6c1xe';

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var string
     */
    private $pollUrl;

    public function __construct(ClientInterface $client) {
        $this->client = $client;
    }

    public function findQuotes(SessionParameters $parameters) {
        $this->createSession($parameters);
        return $this->poll();
    }

    private function createSession(SessionParameters $parameters) {
        try {
            $parametersArray = $parameters->toArray();

            $request = $this
                ->client
                ->post(static::LIVE_PRICING, ['form_params' => $parametersArray]);

            if (!isset($request->getHeaders()['Location'][0])) {
                throw new \Exception('Location not found for the poll');
            }

            $this->setPollUrl($request->getHeaders()['Location'][0], $parametersArray['apiKey']);
            sleep(1);
        } catch (BadResponseException $e) {
            //TODO
            echo $e->getMessage();die;
        }
    }

    private function poll() {
        try {
            $request = $this
                ->client
                ->get($this->pollUrl);

            //TODO FIXME
            return \GuzzleHttp\json_decode($request->getBody()->getContents());
        } catch (BadResponseException $e) {
            //print_r($e->getResponse());
        }
    }

    /**
     * @param $url
     * @param $apiKey
     */
    private function setPollUrl($url, $apiKey) {
        $this->pollUrl = "$url?apiKey=$apiKey&sorttype=price&sortorder=desc";
    }
}
