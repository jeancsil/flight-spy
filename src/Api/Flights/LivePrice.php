<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Flights;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Api\Http\TransportAwareTrait;

class LivePrice
{
    use TransportAwareTrait;

    /**
     * @param SessionParameters $parameters
     * @param bool $newSession
     * @return array
     */
    public function getDeals(SessionParameters $parameters, $newSession)
    {
        return $this->transport->findQuotes($parameters, $newSession);
    }

    /**
     * @param array $sessionParameters
     * @return array
     */
    public function getMultiDeals(array $sessionParameters)
    {
        $responses = [];
        static $requests = 0;
        foreach ($sessionParameters as $sessionParameter) {
            if (!$sessionParameter instanceof SessionParameters) {
                throw new \LogicException(sprintf('Instance of SessionParameters need. Given %s.', $sessionParameter));
            }

            $newSession = ($requests % 20) == 0;
            if ($response = $this->getDeals($sessionParameter, $newSession)) {
                $responses[] = $response;
                $requests++;
            }
        }

        return $responses;
    }
}
