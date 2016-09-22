<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
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
