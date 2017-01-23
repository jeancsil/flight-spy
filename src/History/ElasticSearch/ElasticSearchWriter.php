<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\History\ElasticSearch;

use Jeancsil\FlightSpy\Service\ElasticSearch\Client;

class ElasticSearchWriter extends ResultWriter
{
    use ElasticSearchProcessorTrait;

    /**
     * @var string
     */
    protected $indexName;

    /**
     * @var string
     */
    protected $typeName;

    public function write(array $document)
    {
        $documents = $this->processor->process($document);
        foreach ($documents as $document) {
            $params = [
                'index' => $this->indexName,
                'type' => $this->typeName,
                'body' => $document
            ];

            Client::getInstance()
                ->index($params);
        }
    }

    public function configureIndex($indexName)
    {
        $this->indexName = $indexName;
    }

    public function configureType($typeName)
    {
        $this->typeName = $typeName;
    }
}
