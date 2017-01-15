<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\History\ElasticSearch;

abstract class ResultWriter
{
    /**
     * @param array $response
     * @return void
     */
    abstract public function write(array $response);
}
