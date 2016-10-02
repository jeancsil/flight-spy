<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
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

        $parameters = $this
            ->sessionParametersFactory
            ->createFromConfigFile($configFile);

        if (!$response = $this->livePricesApi->getMultiDeals($parameters)) {
            return;
        }

        $this->livePricePostProcessor
            ->setSessionParameters($parameters[0])
            ->multiProcess($response);
    }
}
