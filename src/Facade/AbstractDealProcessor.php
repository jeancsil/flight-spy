<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Facade;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParametersFactory;
use Jeancsil\FlightSpy\Api\Flights\LivePrice;
use Jeancsil\FlightSpy\Api\Processor\LivePricePostProcessor;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractDealProcessor
{
    /**
     * @var SessionParametersFactory
     */
    protected $sessionParametersFactory;

    /**
     * @var LivePrice
     */
    protected $livePricesApi;

    /**
     * @var LivePricePostProcessor
     */
    protected $livePricePostProcessor;

    public function __construct(
        SessionParametersFactory $factory,
        LivePrice $livePriceApi,
        LivePricePostProcessor $livePricePostProcessor
    ) {
        $this->sessionParametersFactory = $factory;
        $this->livePricesApi = $livePriceApi;
        $this->livePricePostProcessor = $livePricePostProcessor;
    }

    /**
     * @param InputInterface $input
     */
    abstract public function process(InputInterface $input);
}
