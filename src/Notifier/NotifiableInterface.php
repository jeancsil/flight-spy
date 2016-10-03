<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;

interface NotifiableInterface
{
    /**
     * @param array $deals
     * @param SessionParameters $sessionParameters
     * @return void
     */
    public function notify(array $deals, SessionParameters $sessionParameters);

    /**
     * @param array $deals
     * @param SessionParameters $parameters
     * @return Notification
     */
    public function createNotification(array $deals, SessionParameters $parameters);
}
