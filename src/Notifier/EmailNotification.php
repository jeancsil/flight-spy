<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

class EmailNotification implements Notification {
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
    public $ready;

    /**
     * @return boolean
     */
    public function isReady()
    {
        return !empty($this->from) && !empty($this->to) && !empty($this->subject) && !empty($this->html);
    }
}
