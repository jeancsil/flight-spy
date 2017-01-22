<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\History\ElasticSearch;

trait ElasticSearchWriterTrait
{
    /**
     * @var ElasticSearchWriter
     */
    protected $writer;

    /**
     * @param ElasticSearchWriter $writer
     */
    public function setElasticSearchWriter(ElasticSearchWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @return ElasticSearchWriter
     */
    public function getElasticSearchWriter()
    {
        return $this->writer;
    }
}
