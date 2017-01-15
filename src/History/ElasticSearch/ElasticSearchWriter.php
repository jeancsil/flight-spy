<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\History\ElasticSearch;

use Jeancsil\FlightSpy\Service\ElasticSearch\Client;

class ElasticSearchWriter extends ResultWriter
{
    /**
     * @var string
     */
    protected $indexName;

    /**
     * @var string
     */
    protected $typeName;

    public function write(array $response)
    {
        $params = [
            'index' => $this->indexName,
            'type' => $this->typeName,
//            'id' => 'my_id',
            'body' => $response
        ];

        $response = Client::getInstance()
            ->index($params);

        print_r($response);
    }

    public function configureIndex($indexName) {
        $this->indexName = $indexName;
    }

    public function configureType($typeName) {
        $this->typeName = $typeName;
    }
}
