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
     * @param Deal[] $deals
     * @param SessionParameters $sessionParameters
     * @return void
     */
    public function notify(array $deals, SessionParameters $sessionParameters);

    /**
     * @param Deal $deal
     * @param string $notifyTo
     * @return boolean
     */
    public function wasNotified(Deal $deal, $notifyTo);

    /**
     * @param SessionParameters $parameters
     * @param Deal[] $deals
     * @return Notification[]
     */
    public function createNotifications(SessionParameters $parameters, array $deals = []);
}
