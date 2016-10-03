<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;

interface NotifiableInterface
{
    public function notify(array $deals, SessionParameters $sessionParameters);

    public function createNotification(array $deals, SessionParameters $parameters);
}
