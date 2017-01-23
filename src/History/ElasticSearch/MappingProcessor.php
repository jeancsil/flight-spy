<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\History\ElasticSearch;

class MappingProcessor implements Processor
{
    /**
     * @var array
     */
    private $dataCache;

    /** @inheritdoc */
    public function process(array $data)
    {
        $this->dataCache = $data;

        $mappedDocuments = [];
        foreach ($data['Itineraries'] as $itinerary) {
            $outboundCarrier = $this->getCarrier($itinerary['OutboundLegId']);
            $inboundCarrier = $this->getCarrier($itinerary['InboundLegId']);

            foreach ($itinerary['PricingOptions'] as $priceOption) {
                $mappedData = [];
                $mappedData['Creation'] = $this->formatDate('now');
                $mappedData['SessionKey'] = $data['SessionKey'];
                $mappedData['Status'] = $data['Status'];
                $mappedData['Adults'] = $data['Query']['Adults'];
                $mappedData['Children'] = $data['Query']['Children'];
                $mappedData['Infants'] = $data['Query']['Infants'];
                $mappedData['CabinClass'] = $data['Query']['CabinClass'];
                $mappedData['Country'] = $data['Query']['Country'];
                $mappedData['OriginPlace'] = $this->getPlaceNameById($data['Query']['OriginPlace']);
                $mappedData['DestinationPlace'] = $this->getPlaceNameById($data['Query']['DestinationPlace']);
                $mappedData['Locale'] = $data['Query']['Locale'];
                $mappedData['GroupPricing'] = $data['Query']['GroupPricing'];
                $mappedData['Departure'] = $this->getDepartureDate($itinerary['OutboundLegId']);
                $mappedData['Arrival'] = $this->getArrivalDate($itinerary['InboundLegId']);
                $mappedData['Price'] = $this->getPrice($priceOption);
                $mappedData['PriceFormatted'] = $this->getPrice($priceOption, $data['Query']['Currency']);
                $mappedData['OutboundAirline'] = $outboundCarrier['Name'];
                $mappedData['InboundAirline'] = $inboundCarrier['Name'];
                $mappedData['DeeplinkUrl'] = $priceOption['DeeplinkUrl'];

                $mappedDocuments[] = $mappedData;
            }
        }

        $this->dataCache = null;

        return $mappedDocuments;
    }

    private function formatDate($date)
    {
        return (new \DateTime($date))
            ->format(\DATE_ATOM);
    }

    private function getDepartureDate($legId)
    {
        foreach ($this->dataCache['Legs'] as $leg) {
            if ($leg['Id'] == $legId) {
                return $this->formatDate($leg['Departure']);
            }
        }
    }

    private function getArrivalDate($legId)
    {
        foreach ($this->dataCache['Legs'] as $leg) {
            if ($leg['Id'] == $legId) {
                return $this->formatDate($leg['Arrival']);
            }
        }
    }

    /**
     * @param $placeId
     * @return string
     */
    private function getPlaceNameById($placeId)
    {
        foreach ($this->dataCache['Places'] as $place) {
            if ($place['Id'] == $placeId) {
                return $place['Name'];
            }
        }
    }

    /**
     * @param $legId
     * @return array
     */
    private function getCarrier($legId)
    {
        foreach ($this->dataCache['Legs'] as $leg) {
            if ($leg['Id'] == $legId) {
                foreach ($this->dataCache['Carriers'] as $carrier) {
                    if ($carrier['Id'] == $leg['Carriers'][0]) {
                        return $carrier;
                    }
                }
            }
        }
    }

    /**
     * @param $priceOption
     * @param $currencyCode
     * @return string
     */
    private function getPrice($priceOption, $currencyCode = null)
    {
        if ($currencyCode == null) {
            return $priceOption['Price'];
        }

        foreach ($this->dataCache['Currencies'] as $currency) {
            if ($currency['Code'] == $currencyCode) {
                if ($currency['SymbolOnLeft']) {
                    return $currency['Symbol'] . $priceOption['Price'];
                }

                return $priceOption['Price'] . $currency['Symbol'];
            }
        }
    }
}
