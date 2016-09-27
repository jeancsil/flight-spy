<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Factory;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Notifier\EmailNotification;

class EmailNotifierFactory
{
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
    public function __construct($from, $to, $subject, $htmlTemplate)
    {
        $this->notification = new EmailNotification();
        $this->notification->from = $from;
        $this->notification->to = $to;
        $this->notification->subject = $subject;
        $this->notification->html = $htmlTemplate;
    }

    /**
     * @param array $dealsInfo
     * @param SessionParameters $sessionParameters
     * @return EmailNotification
     */
    public function createNotification(array $dealsInfo, SessionParameters $sessionParameters)
    {
        $message = sprintf(
            '<br />From: %s.
            <br />To: %s.
            <br />Departure: %s.
            <br />Arrival: %s.
            <br />Currency: %s
            <br />Adults: %s<br />',
            $sessionParameters->originPlace,
            $sessionParameters->destinationPlace,
            $sessionParameters->outboundDate,
            $sessionParameters->inboundDate,
            $sessionParameters->currency,
            $sessionParameters->adults
        );

        foreach ($dealsInfo as $dealInfo) {
            $dealInfo = (object) $dealInfo;
            $message .= '<h3>' . $dealInfo->agent . '</h3>';
            $message .= '<h4>' . number_format($dealInfo->price) . '</h4>';

            if ($dealInfo->deepLinkUrl) {
                $message .= "<a href=$dealInfo->deepLinkUrl>link</a>";
            }

            $message .= '<br />';
        }

        $this->notification->html = str_replace(
            '{message}',
            $message,
            $this->notification->html
        );

        if ($message) {
            $this->notification->ready = true;
        }

        return $this->notification;
    }
}
