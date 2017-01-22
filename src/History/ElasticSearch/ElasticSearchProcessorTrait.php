<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\History\ElasticSearch;

trait ElasticSearchProcessorTrait
{
    /**
     * @var Processor
     */
    protected $processor;

   /**
     * @return Processor
     */
    public function getProcessor()
    {
        if ($this->processor != null) {
            return $this->processor;
        }

        return new DummyProcessor();
    }

    /**
     * @param Processor $processor
     * @return $this
     */
    public function setProcessor(Processor $processor)
    {
        $this->processor = $processor;

        return $this;
    }
}
