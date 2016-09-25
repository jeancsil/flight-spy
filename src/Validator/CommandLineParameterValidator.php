<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Validator;

use Jeancsil\FlightSpy\Entity\Parameter;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;

class CommandLineParameterValidator implements ValidatorInterface
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @param $instance
     * @return $this
     */
    public function setInstance($instance) {
        if (!$instance instanceof InputInterface) {
            throw new \LogicException(
                sprintf('$instance must be instance of InputInterface. %s given', $instance)
            );
        }

        $this->input = $instance;

        return $this;
    }

    /**
     * TODO validate all fields
     * @throw ValidationException
     */
    public function validate() {
        $configFile = $this->input->getOption(Parameter::FILE);
        $from = $this->input->getOption(Parameter::FROM);
        $to = $this->input->getOption(Parameter::TO);
        $departureDate = $this->input->getOption(Parameter::DEPARTURE_DATE);
        $returnDate = $this->input->getOption(Parameter::RETURN_DATE);
        $maxPrice = $this->input->getOption(Parameter::MAX_PRICE);

        if ($configFile && file_exists($configFile)) {
            return; // TODO validate file content
            //throw new \InvalidArgumentException(sprintf('File not found: %s', $configFile));
        }

        $options = [
            '--from' => $from,
            '--to' => $to,
            '--departure' => $departureDate,
            '--arrival' => $returnDate,
            '--max-price' => $maxPrice
        ];

        foreach ($options as $longForm => $option) {
            if (!$option) {
                throw new InvalidOptionException("Option $longForm not defined");
            }
        }
    }
}
