<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Command;

use Jeancsil\FlightSpy\Command\Entity\Parameter;
use Jeancsil\FlightSpy\Facade\MultiDealFacade;
use Jeancsil\FlightSpy\Validator\ValidatorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Psr\Log\LoggerInterface;

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
        ;
    }

    /** @inheritdoc*/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getValidator()
            ->setTarget($input)
            ->validate();

        try {
            $this->getMultiDeal()->process($input);
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
     * @return LoggerInterface
     */
    private function getLogger()
    {
        return $this
            ->getContainer()
            ->get('jeancsil_flight_spy.logger.array_logger');
    }
}
