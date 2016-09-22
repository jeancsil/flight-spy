<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Factory;

use Jeancsil\FlightSpy\Notifier\EmailNotification;

class EmailNotifierFactory {
    /**
     * @var EmailNotification
     */
    private $notification;

    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $htmlTemplate
     */
    public function __construct($from, $to, $subject, $htmlTemplate) {
        $this->notification = new EmailNotification();
        $this->notification->from = $from;
        $this->notification->to = $to;
        $this->notification->subject = $subject;
        $this->notification->html = $htmlTemplate;
    }

    /**
     * @param string $htmlMessage
     * @return EmailNotification
     */
    public function createNotification($htmlMessage) {
        $this->notification->html = str_replace(
            '{message}',
            $htmlMessage,
            $this->notification->html
        );

        if (!$htmlMessage) {
            $this->notification->ready = false;
        }

        return $this->notification;
    }
}
