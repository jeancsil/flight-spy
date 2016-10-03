<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Email;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Notifier\NotifiableInterface;
use Postmark\PostmarkClient;

class Notifier implements NotifiableInterface
{
    /**
     * @var PostmarkClient
     */
    private $mailer;

    private $from;
    private $to;
    private $subject;
    private $html;

    public function __construct(PostmarkClient $mailer, $from, $to, $subject, $html)
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->html = $html;
    }

    public function notify(array $deals, SessionParameters $sessionParameters)
    {
        $notification = $this->createNotification($deals, $sessionParameters);

        $this->mailer->sendEmail(
            $notification->from,
            $notification->to,
            $notification->subject,
            $notification->html
        );
    }

    /**
     * @param array $deals
     * @param SessionParameters $parameters
     * @return EmailNotification
     */
    public function createNotification(array $deals, SessionParameters $parameters)
    {
        $message = sprintf(
            '<br />From: %s.
            <br />To: %s.
            <br />Departure: %s.
            <br />Arrival: %s.
            <br />Currency: %s
            <br />Adults: %s<br />',
            $parameters->originPlace,
            $parameters->destinationPlace,
            $parameters->outboundDate,
            $parameters->inboundDate,
            $parameters->currency,
            $parameters->adults
        );

        foreach ($deals as $deal) {
            $deal = (object) $deal;
            $message .= '<h3>' . $deal->agent . '</h3>';
            $message .= '<h4>' . number_format($deal->price) . '</h4>';

            if ($deal->deepLinkUrl) {
                $message .= "<a href=$deal->deepLinkUrl>link</a>";
            }

            $message .= '<br />';
        }

        $notification = new EmailNotification();
        $notification->from = $this->from;
        $notification->to = $this->to;
        $notification->subject = $this->subject;
        $notification->html = $this->html;

        $notification->html = str_replace(
            '{message}',
            $message,
            $notification->html
        );

        if (!empty($dealsInfo)) {
            $notification->ready = true;
        }

        return $notification;
    }
}
