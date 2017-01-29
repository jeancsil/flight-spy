<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Service\Currency;

class PriceFormatter
{
    const CURRENCIES_FILE = 'currencies.json';

    private $currencies;

    public function __construct($resourcesDir)
    {
        $this->initializeCurrenciesFile($resourcesDir);
    }

    /**
     * @param string $price
     * @param string $currencyCode
     * @return string
     */
    public function format($price, $currencyCode) {
        foreach ($this->currencies as $currency) {
            if ($currency['Code'] == $currencyCode) {
                if ($currency['SymbolOnLeft'] == 'true') {
                    return $currency['Symbol'] .
                        number_format(
                            $price,
                            2,
                            $currency['DecimalSeparator'],
                            $currency['ThousandsSeparator']
                        );
                }

                return number_format(
                    $price,
                    2,
                    $currency['DecimalSeparator'],
                    $currency['ThousandsSeparator']
                ) . $currency['Symbol'];
            }
        }
    }

    /**
     * @param $resourcesDir
     */
    private function initializeCurrenciesFile($resourcesDir)
    {
        if (!$this->currencies) {
            $currenciesFile = getcwd() . '/' . $resourcesDir . '/' . static::CURRENCIES_FILE;
            $currenciesArray = json_decode(file_get_contents($currenciesFile), true);
            $this->currencies = $currenciesArray['ReferenceServiceResponseDto']['Currencies']['CurrencyDto'];
        }
    }
}
