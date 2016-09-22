<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

use Mailgun\Mailgun;

class EmailNotifier implements NotifiableInterface {
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $domainName;

    public function __construct($apiKey, $domainName) {
        $this->apiKey = $apiKey;
        $this->domainName = $domainName;
    }

    public function notify(Notification $notification) {
        if (!$notification instanceof EmailNotification) {
            throw new \LogicException(
                sprintf('Expecting instance of EmailNotification. %s given.', get_class($notification))
            );
        }

        if (!$notification->isReady()) {
            return;
        }

        $mail = new Mailgun($this->apiKey);
        $mail->sendMessage(
            $this->domainName,
            [
                'from' => $notification->from,
                'to' => $notification->to,
                'subject' => $notification->subject,
                'html' => $notification->html
            ]
        );
    }
}
