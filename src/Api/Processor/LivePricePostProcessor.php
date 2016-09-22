<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Processor;

use Jeancsil\FlightSpy\Notifier\Factory\EmailNotifierFactoryAwareTrait;
use Jeancsil\FlightSpy\Notifier\NotifierAwareTrait;
use Psr\Log\LoggerAwareTrait;

class LivePricePostProcessor {
    use NotifierAwareTrait;
    use EmailNotifierFactoryAwareTrait;
    use LoggerAwareTrait;

    const MAX_PARSED_DEALS = 5;

    /**
     * @var float
     */
    private $maximumPrice;

    /**
     * @var array
     */
    private $agents = [];

    public function process(\stdClass $response) {
        //$this->logger->debug(sprintf("Response received: %s", json_encode($response, true)));
        $itineraries = $response->Itineraries;
        $cheaperItineraries = array_slice($itineraries, 0, static::MAX_PARSED_DEALS);
        $this->agents = $response->Agents;
        $htmlMessage = '';

        $resultCount = 1;
        foreach ($cheaperItineraries as $itinerary) {
            $this->logger->debug('Verifying itinerary #'. $resultCount++);

            if (!isset($itinerary->PricingOptions[0])) {
                $this->logger->debug('No PricingOptions found.');
                continue;
            }

            $deepLinkUrl = 'no deeplink found';
            if (isset($itinerary->PricingOptions[0]->DeeplinkUrl)) {
                $deepLinkUrl = $itinerary->PricingOptions[0]->DeeplinkUrl;
            }

            $price = $itinerary->PricingOptions[0]->Price;

            if ($price <= $this->maximumPrice) {
                $agent = $this->getAgentName($itinerary->PricingOptions[0]->Agents);
                $this->logger->debug("Deal found: ($price) ($agent) ($deepLinkUrl)");
                $htmlMessage .= " -> ($agent) ($price) ($deepLinkUrl)<br />";
                continue;
            }

            $this->logger->debug("skipping...");
        }

        $this->notifier->notify(
            $this->emailNotifierFactory->createNotification($htmlMessage)
        );
    }

    public function defineDealMaxPrice($maximumPrice) {
        if (!is_numeric($maximumPrice)) {
            throw new \InvalidArgumentException(sprintf('Expecting numeric received %s', gettype($maximumPrice)));
        }

        $this->maximumPrice = $maximumPrice;

        return $this;
    }

    private function getAgentName(array $agents) {
        $agentId = 0;
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
