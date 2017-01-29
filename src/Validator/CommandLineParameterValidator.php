<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Validator;

use Jeancsil\FlightSpy\Command\Entity\Parameter;
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
    public function setTarget($instance)
    {
        if (!$instance instanceof InputInterface) {
            throw new \LogicException(
                sprintf('$instance must be instance of InputInterface. %s given', $instance)
            );
        }

        $this->input = $instance;

        return $this;
    }

    /**
     * @throw ValidationException
     */
    public function validate()
    {
        $configFile = $this->input->getOption(Parameter::FILE);

        if (!$configFile || !file_exists($configFile)) {
            throw new \InvalidArgumentException(sprintf('File not found: %s', $configFile));
        }
    }
}
