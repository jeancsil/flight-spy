<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Service\ElasticSearch;

interface Processor
{
    /**
     * @param array $data
     * @return array
     */
    public function process(array $data);
}
