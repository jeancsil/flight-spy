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

    public function getDeals(SessionParameters $parameters) {
        return $this->transport->findQuotes($parameters);
    }
}
