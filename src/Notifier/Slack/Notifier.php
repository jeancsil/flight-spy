<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Slack;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Service\ElasticSearch\ElasticSearchWriterTrait;
use Jeancsil\FlightSpy\Service\ElasticSearch\ElasticSearchRequester;
use Jeancsil\FlightSpy\Notifier\Deal;
use Jeancsil\FlightSpy\Notifier\NotifiableInterface;
use Maknz\Slack\Client;

class Notifier implements NotifiableInterface
{
    use ElasticSearchWriterTrait;

    /**
     * @var ElasticSearchRequester
     */
    private $elasticSearchRequester;
    /**
     * @var Client
     */
    private $slackClient;
    /**
     * @var string
     */
    private $slackUserName;

    public function __construct(Client $slackClient, $slackUserName)
    {
        $this->slackClient = $slackClient;
        $this->slackUserName = $slackUserName;
    }

    /** @inheritdoc */
    public function notify(array $deals, SessionParameters $sessionParameters)
    {
        $notifications = $this->createNotifications($sessionParameters, $deals);

        /**
         * @var string $identifier
         * @var SlackNotification $notification
         */
        foreach ($notifications as $identifier => $notification) {
            $this->slackClient
                ->createMessage()
                ->send($notification->message);

            $this->elasticSearchWriter
                ->writeOne([
                    'identifier' => $identifier,
                    'notified' => $notification->to
                ]);
        }
    }

    /** @inheritdoc */
    public function wasNotified(Deal $deal, $notifyTo)
    {
        return $this->elasticSearchRequester
            ->wasNotified(
                $deal->getIdentifier(),
                $notifyTo
            );
    }

    /** @inheritdoc */
    public function createNotifications(SessionParameters $parameters, array $deals = [])
    {
        $notifications = [];
        /** @var Deal $deal */
        foreach ($deals as $deal) {
            $to = "@{$this->slackUserName}";

            if ($this->wasNotified($deal, $to)) {
                continue;
            }

            $notification = new SlackNotification();
            $notification->to = $to;
            $message = $deal->getAgentName() . ' ';
            $message.= $parameters->currency . ' *' . number_format($deal->getPrice()) . '* ';

            if ($deal->getDeepLinkUrl()) {
                $message .=  PHP_EOL . "<{$deal->getDeepLinkUrl()}|Deep Link>";
            }

            $notification->message = $message;
            $notifications[$deal->getIdentifier()] = $notification;
        }

        return $notifications;
    }

    /**
     * @param ElasticSearchRequester $elasticSearchRequester
     */
    public function setElasticSearchRequester(ElasticSearchRequester $elasticSearchRequester)
    {
        $this->elasticSearchRequester = $elasticSearchRequester;
    }
}
