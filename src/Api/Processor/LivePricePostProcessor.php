<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Processor;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Notifier\NotifiableInterface;
use Psr\Log\LoggerAwareTrait;

class LivePricePostProcessor
{
    use LoggerAwareTrait;

    const MAX_PARSED_DEALS = 5;

    /**
     * @var NotifiableInterface[]
     */
    private $notifiers = [];

    /**
     * @var SessionParameters
     */
    private $sessionParameters;

    /**
     * @var array
     */
    private $agents = [];

    /**
     * @param \Jeancsil\FlightSpy\Notifier\NotifiableInterface $notifier
     */
    public function addNotifier(NotifiableInterface $notifier)
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * @param array $deals
     */
    public function notifyAll(array $deals)
    {
        foreach ($this->notifiers as $notifier) {
            $notifier->notify($deals, $this->sessionParameters);
        }
    }

    /**
     * @param array $responses
     */
    public function multiProcess(array $responses)
    {
        $deals = [];
        foreach ($responses as $response) {
            $deals = array_merge($deals, $this->doProcess($response));
        }

        $this->notifyAll($deals);
    }

    /**
     * @param \stdClass $response
     */
    public function singleProcess(\stdClass $response)
    {
        $this->notifyAll($this->doProcess($response));
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
            if ($price <= $this->sessionParameters->getMaxPrice()) {
                $this->logger->debug(
                    sprintf("%sDeal found (%s)%s", chr(27) . '[1;32m', $price, chr(27) . "[0m")
                );

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
