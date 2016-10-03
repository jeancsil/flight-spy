<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Slack;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Notifier\NotifiableInterface;
use Maknz\Slack\Client;

class Notifier implements NotifiableInterface
{
    /**
     * @var Client
     */
    private $slackClient;

    public function __construct(Client $slackClient)
    {
        $this->slackClient = $slackClient;
    }

    /**
     * @param array $deals
     * @param SessionParameters $sessionParameters
     */
    public function notify(array $deals, SessionParameters $sessionParameters)
    {
        $notification = $this->createNotification($deals, $sessionParameters);

        $this->slackClient
            ->createMessage()
            ->send($notification->message);
    }

    /**
     * @param array $deals
     * @param SessionParameters $parameters
     * @return SlackNotification
     */
    public function createNotification(array $deals, SessionParameters $parameters)
    {
        if (empty($deals)) {
            return;
        }

        $EOL = PHP_EOL;

        $notification = new SlackNotification();
        $notification->to = '@jesilva';

        $message = sprintf(
            "`FROM: %s`.$EOL`TO: %s`.$EOL`DEPARTURE: %s`.$EOL`ARRIVAL: %s`.$EOL`ADULTS: %s`$EOL`KIDS: %s`$EOL$EOL",
            $parameters->originPlace,
            $parameters->destinationPlace,
            (new \DateTime($parameters->outboundDate))->format('d.m.Y'),
            (new \DateTime($parameters->inboundDate))->format('d.m.Y'),
            $parameters->adults,
            $parameters->infants + $parameters->children
        );

        foreach ($deals as $deal) {
            $deal = (object) $deal;
            $message .= '_' . $deal->agent . '_ ';
            $message .= '*' . $parameters->currency . ' ' . number_format($deal->price) . '* ';

            if ($deal->deepLinkUrl) {
                $message .= "<$deal->deepLinkUrl|buy>" . PHP_EOL;
            }

            $message .= PHP_EOL;
        }

        $notification->message = $message;

        return $notification;
    }
}
