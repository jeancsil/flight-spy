<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Http;

trait TransportAwareTrait
{
    /**
     * @var Transport
     */
    private $transport;

    /**
     * @param Transport $transport
     */
    public function setTransport(Transport $transport) {
        $this->transport = $transport;
    }
}
