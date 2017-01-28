<?php
namespace Jeancsil\FlightSpy\Service\ElasticSearch;

/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
class ElasticSearchRequester
{
    use ConfiguratorTrait;

    /**
     * @param string $identifier
     * @param string $notifyTo
     * @return boolean
     */
    public function wasNotified($identifier, $notifyTo)
    {
        $params = [
            'index' => $this->indexName,
            'type' => $this->typeName,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            ['term' => ['identifier' => $identifier]],
                            ['term' => ['notified' => $notifyTo]]
                        ]
                    ]
                ]
            ]
        ];

        $response = Client::getInstance()->search($params);

        if (isset($response['hits']['total'])) {
            return $response['hits']['total'] > 0;
        }

        return false;
    }
}
