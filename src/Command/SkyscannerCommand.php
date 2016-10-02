<?php
namespace Jeancsil\FlightSpy\Command;

use Jeancsil\FlightSpy\Command\Entity\Parameter;
use Jeancsil\FlightSpy\Facade\MultiDealFacade;
use Jeancsil\FlightSpy\Facade\SingleDealFacade;
use Jeancsil\FlightSpy\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->addOption(
                Parameter::FILE,
                null,
                InputOption::VALUE_OPTIONAL,
                'Load all your trips watcher from a config file (JSON)'
            )

            ->addOption(
                Parameter::FROM,
                null,
                InputOption::VALUE_OPTIONAL,
                'Starting point of your trip.'
            )

            ->addOption(
                Parameter::TO,
                null,
                InputOption::VALUE_OPTIONAL,
                'Your destiny.'
            )

            ->addOption(
                Parameter::DEPARTURE_DATE,
                null,
                InputOption::VALUE_OPTIONAL,
                'The departure date (dd-mm-yyyy).'
            )

            ->addOption(
                Parameter::RETURN_DATE,
                null,
                InputOption::VALUE_OPTIONAL,
                'The return date (dd-mm-yyyy).'
            )

            ->addOption(
                Parameter::MAX_PRICE,
                null,
                InputOption::VALUE_OPTIONAL,
                'Maximum price to consider as a good deal (1500).'
            )

            ->addOption(
                Parameter::API_KEY,
                null,
                InputOption::VALUE_OPTIONAL,
                'The Skyscanner API key.'
            )

            ->addOption(
                Parameter::LOCATION_SCHEMA,
                null,
                InputOption::VALUE_OPTIONAL,
                'One of the locations schema: Iata, GeoNameCode, GeoNameId, Rnid, Sky.',
                'Sky'
            )
            ->addOption(
                Parameter::COUNTRY,
                null,
                InputOption::VALUE_OPTIONAL,
                'Country code (ISO or a valid one from location schema).'
            )

            ->addOption(
                Parameter::CURRENCY,
                null,
                InputOption::VALUE_OPTIONAL,
                'The currency or every price.'
            )

            ->addOption(
                Parameter::LOCALE,
                null,
                InputOption::VALUE_OPTIONAL,
                'The locale (ISO containing language and country. Eg.: pt-BR, DE-de).'
            )

            ->addOption(
                Parameter::ADULTS,
                null,
                InputOption::VALUE_OPTIONAL,
                'Number of adults. (Between 1 an 8).',
                Parameter::DEFAULT_ADULTS
            )

            ->addOption(
                Parameter::CABIN_CLASS,
                null,
                InputOption::VALUE_OPTIONAL,
                'The cabin class. (Economy, PremiumEconomy, Business, First).',
                Parameter::DEFAULT_CABIN_CLASS
            )

            ->addOption(
                Parameter::CHILDREN,
                null,
                InputOption::VALUE_OPTIONAL,
                'The number of children. (Between 0 and 8).',
                Parameter::DEFAULT_CHILDREN
            )

            ->addOption(
                Parameter::INFANTS,
                null,
                InputOption::VALUE_OPTIONAL,
                'The number of infants. Cannot exceeds adults.',
                Parameter::DEFAULT_INFANTS
            )

            ->addOption(
                Parameter::GROUP_PRICING,
                null,
                InputOption::VALUE_OPTIONAL,
                'Show price per adult.',
                Parameter::DEFAULT_GROUP_PRICING
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getValidator()
            ->setTarget($input)
            ->validate();

        try {
            $this->getMultiDeal()->process($input);
            $this->getSingleDeal()->process($input);
        } catch (\InvalidArgumentException $e) {
            $this->getLogger()
                ->critical(
                    'Exception caught:' . PHP_EOL .
                    $e->getMessage() . PHP_EOL .
                    $e->getFile() . ':' . $e->getLine() . PHP_EOL
                );
        }
    }

    /**
     * @return ValidatorInterface
     */
    private function getValidator()
    {
        return $this
            ->getContainer()
            ->get('jeancsil_flight_spy.validator.command_line_parameter');
    }

    /**
     * @return MultiDealFacade
     */
    private function getMultiDeal()
    {
        return $this->getContainer()
            ->get('jeancsil_flight_spy.facade.multi_deal');
    }

    /**
     * @return SingleDealFacade
     */
    private function getSingleDeal()
    {
        return $this->getContainer()
            ->get('jeancsil_flight_spy.facade.single_deal');
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger()
    {
        return $this
            ->getContainer()
            ->get('jeancsil_flight_spy.logger.array_logger');
    }
}
