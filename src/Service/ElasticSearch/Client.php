<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Service\ElasticSearch;

use Elasticsearch\ClientBuilder;

final class Client
{
    /**
     * @var \Elasticsearch\Client
     */
    private static $instance;

    /**
     * To avoid the direct construction
     */
    private function __construct()
    {
    }

    /**
     * Returns ElasticSearch client object
     * @return \Elasticsearch\Client
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            $logger = ClientBuilder::defaultLogger('flightspy_elasticsearch.log');

            static::$instance = ClientBuilder::create()
                ->setRetries(2)
                ->setLogger($logger)
                ->build();
        }

        return static::$instance;
    }
}
