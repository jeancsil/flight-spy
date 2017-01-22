<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\History\ElasticSearch;

class DummyProcessor implements Processor
{
    public function process(array $data)
    {
        return $data;
    }
}
