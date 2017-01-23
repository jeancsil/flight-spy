<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\History\ElasticSearch\ElasticSearchWriterTrait;
use Jeancsil\FlightSpy\History\ElasticSearch\MappingProcessor;
use Psr\Log\LoggerAwareTrait;

class Transport
{
    use LoggerAwareTrait;
    use ElasticSearchWriterTrait;

    const LIVE_PRICING = '/apiservices/pricing/v1.0';

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var string
     */
    private $pollUrl;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param SessionParameters $parameters
     * @param bool $newSession
     * @return \stdClass
     */
    public function findQuotes(SessionParameters $parameters, $newSession)
    {
        if ($newSession) {
            $this->createSession($parameters);
        }

        return $this->poll();
    }

    /**
     * @param SessionParameters $parameters
     * @throws \Exception
     */
    private function createSession(SessionParameters $parameters)
    {
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
            $this->logger->error(
                sprintf(
                    "[Transport::createSession]\nStatusCode: %d\nBody: %s",
                    $e->getResponse()->getStatusCode(),
                    $e->getResponse()->getBody()
                )
            );

            throw $e;
        }
    }

    /**
     * @return \stdClass
     */
    private function poll()
    {
        try {
            $response = $this
                ->client
                ->get($this->pollUrl);

            $this->logger->info("HTTPStatusCode: " . $response->getStatusCode());

            if ($response->getStatusCode() != 200) {
                return;
            }

            $contents = str_replace("'", '\'', $response->getBody()->getContents());

            $arrayContent = \GuzzleHttp\json_decode($contents, true);
            if (!is_array($arrayContent)) {
                $this->logger->critical(
                    sprintf('Expecting $arrayContent to be an array. %s given.', gettype($arrayContent))
                );
                return;
            }

            $this->getElasticSearchWriter()
                ->setProcessor(new MappingProcessor())
                ->write($arrayContent);

            return (object) $arrayContent;
        } catch (BadResponseException $e) {
            $this->logger->error(
                sprintf(
                    "[Transport::pool]\nStatusCode: %d\nBody: %s",
                    $e->getResponse()->getStatusCode(),
                    $e->getResponse()->getBody()
                )
            );
        } catch (\Exception $e) {
            $this->logger->error(
                sprintf(
                    "[Transport::pool]\nException:\n%s",
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * @param string $url
     * @param string $apiKey
     */
    private function setPollUrl($url, $apiKey)
    {
        $this->pollUrl = "$url?apiKey=$apiKey&sorttype=price&sortorder=asc";
        $this->logger->debug("Pool url is: $this->pollUrl");
    }
}
