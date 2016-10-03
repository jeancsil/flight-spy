<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Slack;

use Jeancsil\FlightSpy\Notifier\Notification;

class SlackNotification implements Notification
{
    /**
     * @var string
     */
    public $to;

    /**
     * @var string
     */
    public $message;
}
