<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Processor;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Notifier\Deal;
use Jeancsil\FlightSpy\Notifier\NotifiableInterface;
use Jeancsil\FlightSpy\Service\Currency\PriceFormatter;
use Psr\Log\LoggerAwareTrait;

class LivePricePostProcessor
{
    use LoggerAwareTrait;

    const MAX_PARSED_DEALS = 50;

    /**
     * @var PriceFormatter
     */
    private $priceFormatter;

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

    public function __construct(PriceFormatter $priceFormatter)
    {
        $this->priceFormatter = $priceFormatter;
    }


    /**
     * @param \Jeancsil\FlightSpy\Notifier\NotifiableInterface $notifier
     */
    public function addNotifier(NotifiableInterface $notifier)
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * @param Deal[] $deals
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
     * @return Deal[]
     */
    private function doProcess(\stdClass $response)
    {
        $itineraries = $response->Itineraries;
        $this->agents = $response->Agents;

        $deals = [];
        $resultCount = 1;
        $cheapestItineraries = array_slice($itineraries, 0, static::MAX_PARSED_DEALS);
        foreach ($cheapestItineraries as $itinerary) {
            $logMessage = sprintf("Verifying itinerary #%s: ", $resultCount++);

            foreach ($itinerary['PricingOptions'] as $pricingOption) {
                $price = $this->getPrice($pricingOption);

                if ($price <= $this->sessionParameters->getMaxPrice()) {
                    $formattedPrice = $this->priceFormatter->format($price, $this->sessionParameters->currency);
                    $this->logger->debug(
                        "$logMessage [{$this->getAgentName($pricingOption)}] Deal found ($formattedPrice)"
                    );

                    $deals[] = new Deal(
                        $this->sessionParameters,
                        $price,
                        $this->getAgentName($pricingOption),
                        $this->getDeepLinkUrl($pricingOption)
                    );
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
