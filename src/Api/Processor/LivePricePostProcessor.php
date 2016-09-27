<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Processor;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Notifier\Factory\EmailNotifierFactoryAwareTrait;
use Jeancsil\FlightSpy\Notifier\NotifierAwareTrait;
use Psr\Log\LoggerAwareTrait;

class LivePricePostProcessor
{
    use NotifierAwareTrait;
    use EmailNotifierFactoryAwareTrait;
    use LoggerAwareTrait;

    const MAX_PARSED_DEALS = 5;

    /**
     * @var SessionParameters
     */
    private $sessionParameters;

    /**
     * @var float
     */
    private $maximumPrice;

    /**
     * @var float
     */
    private $maximumPrices;

    /**
     * @var array
     */
    private $agents = [];

    /**
     * @param array $responses
     */
    public function multiProcess(array $responses)
    {
        $deals = [];
        for ($iteration = 0; $iteration < count($responses); $iteration++) {
            $response = $responses[$iteration];
            $this->defineDealMaxPrice($this->maximumPrices[$iteration]);
            $deals = array_merge($deals, $this->doProcess($response));
        }

        $this->notifier->notify(
            $this->emailNotifierFactory->createNotification($deals, $this->sessionParameters)
        );
    }

    /**
     * @param \stdClass $response
     */
    public function singleProcess(\stdClass $response)
    {
        $this->notifier->notify(
            $this->emailNotifierFactory->createNotification(
                $this->doProcess($response),
                $this->sessionParameters
            )
        );
    }

    /**
     * @param SessionParameters $parameters
     * @return $this
     */
    public function setSessionParameters(SessionParameters $parameters)
    {
        $this->sessionParameters = $parameters;

        return $this;
    }

    /**
     * @param $maximumPrice
     * @return $this
     */
    public function defineDealMaxPrice($maximumPrice)
    {
        if (!is_numeric($maximumPrice)) {
            throw new \InvalidArgumentException(sprintf('Expecting numeric received %s', gettype($maximumPrice)));
        }

        $this->maximumPrice = $maximumPrice;

        return $this;
    }

    /**
     * @param array $maximumPrices
     * @return $this
     */
    public function defineDealMaxPrices(array $maximumPrices)
    {
        foreach ($maximumPrices as $maximumPrice) {
            if (!is_numeric($maximumPrice)) {
                throw new \InvalidArgumentException(sprintf('Expecting numeric received %s', gettype($maximumPrice)));
            }
        }

        $this->maximumPrices = $maximumPrices;

        return $this;
    }

    /**
     * @param \stdClass $response
     * @return array
     */
    private function doProcess(\stdClass $response)
    {
        $itineraries = $response->Itineraries;
        $cheaperItineraries = array_slice($itineraries, 0, static::MAX_PARSED_DEALS);
        $this->agents = $response->Agents;

        $deals = [];
        $resultCount = 1;
        foreach ($cheaperItineraries as $itinerary) {
            $this->logger->debug('Verifying itinerary #'. $resultCount++);

            if (!isset($itinerary->PricingOptions[0])) {
                $this->logger->debug('No PricingOptions found.');
                continue;
            }

            $price = $this->getPrice($itinerary);
            if ($price <= $this->maximumPrice) {
                $this->logger->debug(sprintf("Deal found (%s)", $price));

                $deals[] = [
                    'price' => $price,
                    'agent' => $this->getAgentName($itinerary),
                    'deepLinkUrl' => $this->getDeepLinkUrl($itinerary)
                ];
                continue;
            }
        }

        return $deals;
    }

    /**
     * @param \stdClass $itinerary
     * @return string
     */
    private function getDeepLinkUrl($itinerary)
    {
        if (isset($itinerary->PricingOptions[0]->DeeplinkUrl)) {
            return $itinerary->PricingOptions[0]->DeeplinkUrl;
        }
    }

    /**
     * @param \stdClass $itinerary
     * @return string
     */
    private function getPrice($itinerary)
    {
        return $itinerary->PricingOptions[0]->Price;
    }

    /**
     * @param \stdClass $itinerary
     * @return string
     */
    private function getAgentName($itinerary)
    {
        $agentId = 0;
        $agents = $itinerary->PricingOptions[0]->Agents;
        foreach ($agents as $agent) {
            $agentId = $agent;
            continue;
        }

        foreach ($this->agents as $agent) {
            if ($agent->Id == $agentId) {
                return $agent->Name;
            }
        }
    }
}
