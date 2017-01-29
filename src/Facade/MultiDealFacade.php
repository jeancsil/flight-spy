<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Facade;

use Jeancsil\FlightSpy\Command\Entity\Parameter;
use Symfony\Component\Console\Input\InputInterface;

class MultiDealFacade extends AbstractDealProcessor
{
    /**
     * @param InputInterface $input
     */
    public function process(InputInterface $input)
    {
        if (!$configFile = $input->getOption(Parameter::FILE)) {
            return;
        }

        $sessionParameters = $this
            ->sessionParametersFactory
            ->createFromConfigFile($configFile);

        if (!$response = $this->livePricesApi->getMultiDeals($sessionParameters)) {
            return;
        }

        $this->livePricePostProcessor
            ->setSessionParameters($sessionParameters[0])
            ->multiProcess($response);
    }
}
