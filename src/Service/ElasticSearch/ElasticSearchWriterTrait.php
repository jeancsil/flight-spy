<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Service\ElasticSearch;

trait ElasticSearchWriterTrait
{
    /**
     * @var ElasticSearchWriter
     */
    protected $elasticSearchWriter;

    /**
     * @param ElasticSearchWriter $writer
     */
    public function setElasticSearchWriter(ElasticSearchWriter $writer)
    {
        $this->elasticSearchWriter = $writer;
    }

    /**
     * @return ElasticSearchWriter
     */
    public function getElasticSearchWriter()
    {
        return $this->elasticSearchWriter;
    }
}
