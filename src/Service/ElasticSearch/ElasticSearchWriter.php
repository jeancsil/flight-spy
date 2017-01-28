<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Service\ElasticSearch;

class ElasticSearchWriter extends ResultWriter
{
    use ElasticSearchProcessorTrait;
    use ConfiguratorTrait;

    public function write(array $document)
    {
        $documents = $this
            ->getProcessor()
            ->process($document);

        foreach ($documents as $document) {
            $params = [
                'index' => $this->indexName,
                'type' => $this->typeName,
                'body' => $document
            ];

            Client::getInstance()->index($params);
        }
    }

    public function writeOne(array $document)
    {
        $doc = $this
            ->getProcessor()
            ->process($document);

        $params = [
            'index' => $this->indexName,
            'type' => $this->typeName,
            'body' => $doc
        ];

        Client::getInstance()->index($params);
    }
}
