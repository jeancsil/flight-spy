<?php
namespace Jeancsil\FlightSpy\Command;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParametersFactory;
use Jeancsil\FlightSpy\Api\Flights\LivePrice;
use Jeancsil\FlightSpy\Api\Processor\LivePricePostProcessor;
use Jeancsil\FlightSpy\Entity\Parameter as P;
use Jeancsil\FlightSpy\Validator\ValidatorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption as I;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
class SkyscannerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('flightspy:skyscanner:live_prices')
            ->setDescription('Look for live prices in Skyscanner for the determined filters')
            ->addOption(P::FILE, null, I::VALUE_OPTIONAL, 'Load all your trips watcher from a config file (JSON)')
            ->addOption(P::FROM, null, I::VALUE_OPTIONAL, 'Starting point of your trip.')
            ->addOption(P::TO, null, I::VALUE_OPTIONAL, 'Your destiny.')
            ->addOption(P::DEPARTURE_DATE, null, I::VALUE_OPTIONAL, 'The departure date (dd-mm-yyyy).')
            ->addOption(P::RETURN_DATE, null, I::VALUE_OPTIONAL, 'The return date (dd-mm-yyyy).')
            ->addOption(P::MAX_PRICE, null, I::VALUE_OPTIONAL, 'Maximum price to consider as a good deal (1500).')
            ->addOption(P::API_KEY, null, I::VALUE_OPTIONAL, 'The Skyscanner API key.')
            ->addOption(
                P::LOCATION_SCHEMA,
                null,
                I::VALUE_OPTIONAL,
                'One of the locations schema: Iata, GeoNameCode, GeoNameId, Rnid, Sky.',
                'Sky'
            )
            ->addOption(P::COUNTRY, null, I::VALUE_OPTIONAL, 'Country code (ISO or a valid one from location schema).')
            ->addOption(P::CURRENCY, null, I::VALUE_OPTIONAL, 'The currency or every price.')
            ->addOption(
                P::LOCALE,
                null,
                I::VALUE_OPTIONAL,
                'The locale (ISO containing language and country. Eg.: pt-BR, DE-de).'
            )
            ->addOption(P::ADULTS, null, I::VALUE_OPTIONAL, 'Number of adults. (Between 1 an 8).', P::DEFAULT_ADULTS)
            ->addOption(
                P::CABIN_CLASS,
                null,
                I::VALUE_OPTIONAL,
                'The cabin class. (Economy, PremiumEconomy, Business, First).',
                P::DEFAULT_CABIN_CLASS
            )
            ->addOption(
                P::CHILDREN,
                null,
                I::VALUE_OPTIONAL,
                'The number of children. (Between 0 and 8).',
                P::DEFAULT_CHILDREN
            )
            ->addOption(
                P::INFANTS,
                null,
                I::VALUE_OPTIONAL,
                'The number of infants. Cannot exceeds adults.',
                P::DEFAULT_INFANTS
            )
            ->addOption(P::GROUP_PRICING, null, I::VALUE_OPTIONAL, 'Show price per adult.', P::DEFAULT_GROUP_PRICING)
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getValidator()
            ->setInstance($input)
            ->validate();

        try {
            if ($input->getOption(P::FILE)) {
                $parameters = $this->getSessionParametersFactory()
                    ->createFromConfigFile($input->getOption(P::FILE));

                if (!$response = $this->getLivePricesApi()->getMultiDeals($parameters)) {
                    return;
                }

                $this->getLivePricesProcessor()
                    ->setSessionParameters($parameters[0])
                    ->defineDealMaxPrices($this->getSessionParametersFactory()->getMaxPrices())
                    ->multiProcess($response);
                return;
            }

            $parameters = $this->getSessionParametersFactory()
                ->createFromInput($input);

            if (!$response = $this->getLivePricesApi()->getDeals($parameters)) {
                return;
            }

            $this->getLivePricesProcessor()
                ->setSessionParameters($parameters)
                ->defineDealMaxPrice($input->getOption(P::MAX_PRICE))
                ->singleProcess($response);
        } catch (\InvalidArgumentException $e) {
            echo 'Exception caught:' . PHP_EOL,
            $e->getMessage() . PHP_EOL,
            $e->getFile() . ':' . $e->getLine() . PHP_EOL;
        }
    }

    /**
     * @return ValidatorInterface
     */
    private function getValidator()
    {
        return $this
            ->getContainer()
            ->get('jeancsil_skyscanner_vigilant.validator.command_line_parameter');
    }

    /**
     * @return LivePrice
     */
    private function getLivePricesApi()
    {
        return $this->getContainer()
            ->get('jeancsil_skyscanner_vigilant.api.flights.live_price');
    }

    /**
     * @return LivePricePostProcessor
     */
    private function getLivePricesProcessor()
    {
        return $this->getContainer()
            ->get('jeancsil_skyscanner_vigilant.api_processor.live_prices');
    }

    /**
     * @return SessionParametersFactory
     */
    private function getSessionParametersFactory()
    {
        return $this->getContainer()
            ->get('jeancsil_skyscanner_vigilant.api_data_transfer.session_parameters_factory');
    }
}
