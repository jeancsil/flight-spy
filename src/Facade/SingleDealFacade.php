<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Facade;

use Jeancsil\FlightSpy\Command\Entity\Parameter;
use Symfony\Component\Console\Input\InputInterface;

class SingleDealFacade extends AbstractDealProcessor
{
    /**
     * @param InputInterface $input
     */
    public function process(InputInterface $input)
    {
        if ($input->getOption(Parameter::FILE)) {
            return;
        }

        $parameters = $this
            ->sessionParametersFactory
            ->createFromInput($input);

        if (!$response = $this->livePricesApi->getDeals($parameters)) {
            return;
        }

        $this->livePricePostProcessor
            ->setSessionParameters($parameters)
            ->defineDealMaxPrice($input->getOption(Parameter::MAX_PRICE))
            ->singleProcess($response);
    }
}
