<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\DataTransfer;

/**
 * @see http://business.skyscanner.net/portal/en-GB/Documentation/FlightsLivePricingList#createsession
 *
 * [name] [mandatory?] [description]
 * apiKey	Yes	The API Key to identify the customer	String	Must be a valid API Key
 * country	Yes	The user’s market country	String	ISO country code, or specified location schema
 * currency	Yes	The user’s currency	String	ISO currency code
 * locale	Yes	The user’s localization preference	String	ISO locale code (language and country)
 * originplace	Yes	The origin city or airport	String	Specified location schema, or Skyscanner Rnid
 * destinationplace	Yes	The destination city or airport	String	Specified location schema, or Skyscanner Rnid
 * outbounddate	Yes	The departure date	Date	Formatted as YYYY-mm-dd
 * inbounddate	No	The return date	Date	Formatted as YYYY-mm-dd
 * locationschema	No	The code schema used for locations	String	The supported codes are below
 * cabinclass	No	The Cabin Class	String	The supported codes are below
 * adults	Yes	The number of adults	Int	Defaults to 1 if not specified. Maximum 8
 * children	No	The number of children	Int	Defaults to 0, maximum 8
 * infants	No	The number of infants	Int	Defaults to 0, cannot exceed adults
 * groupPricing	No	Show price-per-adult (false), or price for all passengers (true)	bool	Defaults to false
 */
class SessionParameters {
    public $apiKey;
    public $country;
    public $currency;
    public $locale;
    public $originPlace;
    public $destinationPlace;
    public $outboundDate;
    public $adults;
    public $inboundDate;
    public $locationSchema;
    public $cabinClass;
    public $children;
    public $infants;
    public $groupPricing;

    /**
     * TODO move this method from here
     */
    public function toArray() {
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

        if ($this->groupPricing) {
            $params['groupPricing'] = $this->groupPricing;
        }

        return $params;
    }
}
