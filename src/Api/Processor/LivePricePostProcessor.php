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

    const MAX_PARSED_DEALS = 50;

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
            if (!$response) {
                $this->logger->warning(sprintf('Empty response received: %s', var_export($response, true)));
                continue;
            }

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
     * @param SessionParameters $sessionParameters
     * @return $this
     */
    public function setSessionParameters(SessionParameters $sessionParameters)
    {
        $this->sessionParameters = $sessionParameters;

        return $this;
    }

    /**
     * @param \stdClass $response
     * @return array
     */
    private function doProcess(\stdClass $response)
    {
        $itineraries = $response->Itineraries;
        $cheapestItineraries = array_slice($itineraries, 0, static::MAX_PARSED_DEALS);
        $this->agents = $response->Agents;

        $deals = [];
        $resultCount = 1;
        foreach ($cheapestItineraries as $itinerary) {
            $logMessage = sprintf('Verifying itinerary #%s: ', $resultCount++);

            foreach ($itinerary['PricingOptions'] as $pricingOption) {
                $price = $this->getPrice($pricingOption);
                if ($price <= $this->sessionParameters->getMaxPrice()) {
                    $logMessage .= sprintf("%sDeal found (%s)%s", chr(27) . '[1;32m', $price, chr(27) . "[0m");
                    $this->logger->debug($logMessage);

                    $deals[] = [
                        'price' => $price,
                        'agent' => $this->getAgentName($pricingOption),
                        'deepLinkUrl' => $this->getDeepLinkUrl($pricingOption)
                    ];
                } else {
                    $this->logger->debug(sprintf("%sNot a Deal(%s)%s", chr(27) . '[1;37m', $price, chr(27) . "[0m"));
                }
            }
        }

        return $deals;
    }

    /**
     * @param \stdClass $pricingOptions
     * @return string
     */
    private function getDeepLinkUrl($pricingOptions)
    {
        if (isset($pricingOptions['DeeplinkUrl'])) {
            return $pricingOptions['DeeplinkUrl'];
        }
    }

    /**
     * @param \stdClass $pricingOptions
     * @return string
     */
    private function getPrice($pricingOptions)
    {
        return $pricingOptions['Price'];
    }

    /**
     * @param \stdClass $pricingOptions
     * @return string
     */
    private function getAgentName($pricingOptions)
    {
        $agentId = 0;
        $agents = $pricingOptions['Agents'];
        foreach ($agents as $agent) {
            $agentId = $agent;
            continue;
        }

        foreach ($this->agents as $agent) {
            if ($agent['Id'] == $agentId) {
                return $agent['Name'];
            }
        }
    }
}
