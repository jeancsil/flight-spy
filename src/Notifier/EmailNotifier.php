<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

use Postmark\PostmarkClient;

class EmailNotifier implements NotifiableInterface
{
    /**
     * @var PostmarkClient
     */
    private $mailer;

    public function __construct(PostmarkClient $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notify(Notification $notification)
    {
        if (!$notification instanceof EmailNotification) {
            throw new \LogicException(
                sprintf('Expecting instance of EmailNotification. %s given.', get_class($notification))
            );
        }

        if (!$notification->isReady()) {
            return;
        }

        $this->mailer->sendEmail(
            $notification->from,
            $notification->to,
            $notification->subject,
            $notification->html
        );
    }
}
