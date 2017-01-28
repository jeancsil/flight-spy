<?php
namespace Jeancsil\FlightSpy\Service\ElasticSearch;

/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
trait ConfiguratorTrait
{
    /**
     * @var string
     */
    protected $indexName;

    /**
     * @var string
     */
    protected $typeName;


    /**
     * @param $indexName
     */
    public function configureIndex($indexName)
    {
        $this->indexName = $indexName;
    }

    /**
     * @param string $typeName
     */
    public function configureType($typeName)
    {
        $this->typeName = $typeName;
    }
}
