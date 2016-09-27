<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Factory;

trait EmailNotifierFactoryAwareTrait {
    /**
     * @var EmailNotifierFactory
     */
    private $emailNotifierFactory;

    /**
     * @param EmailNotifierFactory $emailNotifierFactory
     */
    public function setEmailNotifierFactory(EmailNotifierFactory $emailNotifierFactory) {
        $this->emailNotifierFactory = $emailNotifierFactory;
    }
}
