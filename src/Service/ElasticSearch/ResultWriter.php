<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Service\ElasticSearch;

abstract class ResultWriter
{
    /**
     * @param array $document
     * @return void
     */
    abstract public function write(array $document);
}
