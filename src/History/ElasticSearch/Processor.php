<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\History\ElasticSearch;

interface Processor
{
    /**
     * @param array $data
     * @return mixed
     */
    public function process(array $data);
}
