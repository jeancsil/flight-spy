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

    public function process(array $data) {
        $this->dataCache = $data;

        $mappedData = [];
        $mappedData['Creation'] = (new \DateTime())->format(\DATE_ATOM);
        $mappedData['SessionKey'] = $data['SessionKey'];
        $mappedData['Status'] = $data['Status'];
        $mappedData['Query'] = $data['Query'];
        unset($mappedData['Query']['LocationSchema']);

        $mappedData['Query']['OriginPlace'] = $this->getPlaceNameById($data['Query']['OriginPlace']);
        $mappedData['Query']['DestinationPlace'] = $this->getPlaceNameById($data['Query']['DestinationPlace']);
        $mappedData['Query']['OutboundDate'] = $this->formatDate($mappedData['Query']['OutboundDate']);
        $mappedData['Query']['InboundDate'] = $this->formatDate($mappedData['Query']['InboundDate']);

        $count = 0;
        $mappedData['Itineraries'] = [];
        foreach ($data['Itineraries'] as $itinerary) {
            $outboundCarrier = $this->getCarrier($itinerary['OutboundLegId']);
            $inboundCarrier = $this->getCarrier($itinerary['InboundLegId']);

            foreach ($itinerary['PricingOptions'] as $priceOption) {
                $mappedData['Itineraries'][$count] = [
                    'Price' => $priceOption['Price'],
                    'Deeplink' => 'none',//$priceOption['DeeplinkUrl'],
                    'OutboundCarrier' => $outboundCarrier['Name'],
                    'InboundCarrier' => $inboundCarrier['Name'],
                ];
            }

            $count++;
        }

        return $mappedData;
    }

    private function formatDate($date) {
        return (new \DateTime($date))
            ->format(\DATE_ATOM);
    }

    /**
     * @param $placeId
     * @return string
     */
    private function getPlaceNameById($placeId) {
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
    private function getCarrier($legId) {
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
}
