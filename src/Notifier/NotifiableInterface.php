<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

interface NotifiableInterface
{
    public function notify(Notification $notification);
}
