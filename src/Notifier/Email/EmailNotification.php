<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Email;

use Jeancsil\FlightSpy\Notifier\Notification;

class EmailNotification implements Notification
{
    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $to;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $html;

    /**
     * @var bool
     */
    public $ready = false;

    /**
     * @return boolean
     */
    public function isReady()
    {
        return $this->ready === true;
    }
}
