<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\DataTransfer;

use Jeancsil\FlightSpy\Entity\Parameter;
use Symfony\Component\Console\Input\InputInterface;

class SessionParametersFactory
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var array
     */
    private $configCache;

    /**
     * @var array
     */
    private $maxPrices;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $configFile
     * @return Parameter[]
     */
    public function createFromConfigFile($configFile) {
        $configurations = json_decode(file_get_contents($configFile), true);

        $parameters = [];
        $maxPrices = [];
        foreach ($configurations as $configuration) {
            $parameters[] = $this->createFromArray($configuration);
            $maxPrices[] = $this->getValue(Parameter::MAX_PRICE);
        }

        $this->maxPrices = $maxPrices;

        return $parameters;
    }

    /**
     * @param InputInterface $input
     * @return SessionParameters
     */
    public function createFromInput(InputInterface $input) {
        $parameters = new SessionParameters();
        $parameters->apiKey = $input->getOption(Parameter::API_KEY) ?: $this->apiKey;
        $parameters->originPlace = $input->getOption(Parameter::FROM);
        $parameters->destinationPlace = $input->getOption(Parameter::TO);
        $parameters->outboundDate = $input->getOption(Parameter::DEPARTURE_DATE);
        $parameters->inboundDate = $input->getOption(Parameter::RETURN_DATE);
        $parameters->locationSchema = $input->getOption(Parameter::LOCATION_SCHEMA);
        $parameters->country = $input->getOption(Parameter::COUNTRY);
        $parameters->currency = $input->getOption(Parameter::CURRENCY);
        $parameters->locale = $input->getOption(Parameter::LOCALE);
        $parameters->adults = $input->getOption(Parameter::ADULTS);
        $parameters->cabinClass = $input->getOption(Parameter::CABIN_CLASS);
        $parameters->children = $input->getOption(Parameter::CHILDREN);
        $parameters->infants = $input->getOption(Parameter::INFANTS);
        $parameters->groupPricing = $input->getOption(Parameter::GROUP_PRICING);

        return $parameters;
    }

    /**
     * @return array
     */
    public function getMaxPrices() {
        return $this->maxPrices;
    }

    /**
     * @param array $configuration
     * @return SessionParameters
     */
    private function createFromArray(array $configuration) {
        $this->configCache = $configuration;

        $parameters = new SessionParameters();
        $parameters->apiKey = $this->getValue(Parameter::API_KEY, $this->apiKey);
        $parameters->originPlace = $this->getValue(Parameter::FROM);
        $parameters->destinationPlace = $this->getValue(Parameter::TO);
        $parameters->outboundDate = $this->getValue(Parameter::DEPARTURE_DATE);
        $parameters->inboundDate = $this->getValue(Parameter::RETURN_DATE);
        $parameters->locationSchema = $this->getValue(Parameter::LOCATION_SCHEMA, Parameter::DEFAULT_LOCATION_SCHEMA);
        $parameters->country = $this->getValue(Parameter::COUNTRY);
        $parameters->currency = $this->getValue(Parameter::CURRENCY);
        $parameters->locale = $this->getValue(Parameter::LOCALE);
        $parameters->adults = $this->getValue(Parameter::ADULTS, Parameter::DEFAULT_ADULTS);
        $parameters->cabinClass = $this->getValue(Parameter::CABIN_CLASS, Parameter::DEFAULT_CABIN_CLASS);
        $parameters->children = $this->getValue(Parameter::CHILDREN, Parameter::DEFAULT_CHILDREN);
        $parameters->infants = $this->getValue(Parameter::INFANTS, Parameter::DEFAULT_INFANTS);
        $parameters->groupPricing = $this->getValue(Parameter::GROUP_PRICING, Parameter::DEFAULT_GROUP_PRICING);

        return $parameters;
    }

    /**
     * @param string $parameter
     * @param mixed $defaultValue
     * @return mixed
     */
    private function getValue($parameter, $defaultValue = null) {
        if (isset($this->configCache[$parameter])) {
            return $this->configCache[$parameter];
        }

        return $defaultValue;
    }
}
