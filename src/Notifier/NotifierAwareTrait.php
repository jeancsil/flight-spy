<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

trait NotifierAwareTrait {
    /**
     * @var NotifiableInterface
     */
    private $notifier;

    /**
     * @param NotifiableInterface $notifier
     */
    public function setNotifier(NotifiableInterface $notifier) {
        $this->notifier = $notifier;
    }
}
