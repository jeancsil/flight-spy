<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Command\Entity;

class Parameter
{
    const FILE = 'file';
    const FROM = 'from';
    const TO = 'to';
    const DEPARTURE_DATE = 'departure';
    const RETURN_DATE = 'arrival';
    const SEARCH_PERIOD_FROM = 'search-period-from';
    const SEARCH_PERIOD_TO = 'search-period-to';
    const SEARCH_PERIOD_TRAVEL_DAYS = 'search-period-travel-days';
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
