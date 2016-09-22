<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\DataTransfer;

use Jeancsil\FlightSpy\Entity\Parameter;
use Symfony\Component\Console\Input\InputInterface;

class SessionParametersFactory {
    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

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
        $parameters->groupPricing= $input->getOption(Parameter::GROUP_PRICING);

        return $parameters;
    }
}
