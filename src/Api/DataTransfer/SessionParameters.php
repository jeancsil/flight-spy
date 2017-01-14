<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\DataTransfer;

/**
 * @see http://business.skyscanner.net/portal/en-GB/Documentation/FlightsLivePricingList#createsession
 *
 * [name] [mandatory?] [description]
 * apiKey    Yes    The API Key to identify the customer    String    Must be a valid API Key
 * country    Yes    The user’s market country    String    ISO country code, or specified location schema
 * currency    Yes    The user’s currency    String    ISO currency code
 * locale    Yes    The user’s localization preference    String    ISO locale code (language and country)
 * originplace    Yes    The origin city or airport    String    Specified location schema, or Skyscanner Rnid
 * destinationplace    Yes    The destination city or airport    String    Specified location schema, or Skyscanner Rnid
 * outbounddate    Yes    The departure date    Date    Formatted as YYYY-mm-dd
 * inbounddate    No    The return date    Date    Formatted as YYYY-mm-dd
 * locationschema    No    The code schema used for locations    String    The supported codes are below
 * cabinclass    No    The Cabin Class    String    The supported codes are below
 * adults    Yes    The number of adults    Int    Defaults to 1 if not specified. Maximum 8
 * children    No    The number of children    Int    Defaults to 0, maximum 8
 * infants    No    The number of infants    Int    Defaults to 0, cannot exceed adults
 */
class SessionParameters
{
    /**
     * @var string
     */
    public $apiKey;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var string
     */
    public $originPlace;

    /**
     * @var string
     */
    public $destinationPlace;

    /**
     * @var string
     */
    public $outboundDate;

    /**
     * @var integer
     */
    public $adults;

    /**
     * @var string
     */
    public $inboundDate;

    /**
     * @var string
     */
    public $locationSchema;

    /**
     * @var string
     */
    public $cabinClass;

    /**
     * @var integer
     */
    public $children;

    /**
     * @var integer
     */
    public $infants;

    /**
     * @var float
     */
    private $maxPrice;

    /**
     * @return array
     */
    public function toArray()
    {
        $params = [
            'apiKey' => $this->apiKey,
            'country' => $this->country,
            'currency' => $this->currency,
            'locale' => $this->locale,
            'originplace' => $this->originPlace,
            'destinationplace' => $this->destinationPlace,
            'outbounddate' => $this->outboundDate,
            'adults' => $this->adults
        ];

        if ($this->inboundDate) {
            $params['inboundDate'] = $this->inboundDate;
        }

        if ($this->locationSchema) {
            $params['locationSchema'] = $this->locationSchema;
        }

        if ($this->cabinClass) {
            $params['cabinclass'] = $this->cabinClass;
        }

        if ($this->infants) {
            $params['infants'] = $this->infants;
        }

        if ($this->children) {
            $params['children'] = $this->children;
        }

        return $params;
    }

    /**
     * @return float
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * @param float $maxPrice
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = (float) $maxPrice;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s <-> %s out %s in %s %s %s. Adults/Kids/Infants: %d/%d/%d',
            $this->originPlace,
            $this->destinationPlace,
            (new \DateTime($this->outboundDate))->format('d.m.Y'),
            (new \DateTime($this->inboundDate))->format('d.m.Y'),
            $this->currency,
            $this->maxPrice,
            $this->adults,
            $this->children,
            $this->infants
        );
    }
}
