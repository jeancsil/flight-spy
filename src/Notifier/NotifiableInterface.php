<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

interface NotifiableInterface {
    public function notify(Notification $notification);
}
