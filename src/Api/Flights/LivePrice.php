<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
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
     * @return array
     */
    public function getDeals(SessionParameters $parameters) {
        return $this->transport->findQuotes($parameters);
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function getMultiDeals(array $parameters) {
        $response = [];
        foreach ($parameters as $parameter) {
            if (!$parameter instanceof SessionParameters) {
                throw new \LogicException(sprintf('Instance of SessionParameters need. Given %s.', $parameter));
            }

            $response[] = $this->transport->findQuotes($parameter);
        }

        return $response;
    }
}
