<?php
/**
 * @author Jean Silva <jeancsil@gmail.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Entity;

class Parameter {
    const FILE = 'file';
    const FROM = 'from';
    const TO = 'to';
    const DEPARTURE_DATE= 'departure';
    const RETURN_DATE = 'arrival';
    const MAX_PRICE = 'max-price';
    const API_KEY = 'api-key';
    const LOCATION_SCHEMA = 'location-schema';
    const DEFAULT_LOCATION_SCHEMA = 'Sky';
    const COUNTRY = 'country';
    const CURRENCY = 'currency';
    const LOCALE = 'locale';
    const ADULTS = 'adults';
    const DEFAULT_ADULTS = 1;
    const CHILDREN = 'children';
    const DEFAULT_CHILDREN = 0;
    const INFANTS = 'infants';
    const DEFAULT_INFANTS = 0;
    const CABIN_CLASS = 'cabin-class';
    const DEFAULT_CABIN_CLASS = 'Economy';
    const GROUP_PRICING = 'price-per-adult';
    const DEFAULT_GROUP_PRICING = false;
}
