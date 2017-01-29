<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Http;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;

class FakeTransport implements TransportInterface
{
    const RESPONSE_FILE = 'skyscanner_response.json';

    /**
     * @var string
     */
    private $resourcesDir;

    public function __construct($resourcesDir)
    {
        $this->resourcesDir = $resourcesDir;
    }

    /**
     * @param SessionParameters $parameters
     * @param bool $newSession
     * @return \stdClass
     */
    public function findQuotes(SessionParameters $parameters, $newSession)
    {
        try {
            $contents = file_get_contents($this->resourcesDir . '/' . static::RESPONSE_FILE);

            $arrayContent = \GuzzleHttp\json_decode(
                str_replace("'", '\'', $contents),
                true
            );

            return (object) $arrayContent;
        } catch (\Exception $e) {
            // do nothing
        }
    }
}
