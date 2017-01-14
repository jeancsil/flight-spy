<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\DataTransfer;

use Doctrine\Common\Collections\ArrayCollection;

class Period
{
    /**
     * @var int
     */
    private $durationInDays;

    /**
     * @var \Datetime
     */
    private $dateFrom;

    /**
     * @var \Datetime
     */
    private $dateTo;

    /**
     * @var ArrayCollection
     */
    private $combinations;

    /**
     * @param int $durationInDays
     * @param \Datetime $dateFrom
     * @param \Datetime $dateTo
     */
    public function __construct($durationInDays, \Datetime $dateFrom, \Datetime $dateTo)
    {
        $this->durationInDays = $durationInDays;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->combinations = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function generateDateCombinations()
    {
        $possibleDays = $this->dateFrom->diff($this->dateTo)->days;

        $initialDate = clone $this->dateFrom;
        $endDate = clone $this->dateFrom->add(new \DateInterval(sprintf('P%dD', $this->durationInDays)));

        for ($i = 0; $i < $possibleDays; $i++) {
            $this->combinations->add([
                'outboundDate' => $initialDate->format('Y-m-d'),
                'inboundDate' => $endDate->format('Y-m-d')
            ]);

            if ($endDate->diff($this->dateTo)->days == 0) {
                return $this->combinations;
            }

            $initialDate->add($this->getOneDayMore());
            $endDate->add($this->getOneDayMore());
        }
    }

    /**
     * @return int
     */
    public function getDurationInDays()
    {
        return $this->durationInDays;
    }

    /**
     * @return \Datetime
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @return \Datetime
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = sprintf(
            "Duration: %d days\nBetween %s and %s (inclusive)",
            $this->durationInDays,
            $this->dateFrom->format('d/m/Y'),
            $this->dateTo->format('d/m/Y')
        );

        if (!$this->combinations->isEmpty()) {
            $string .= sprintf(
                "\nResulted in these combinations:\n %s",
                var_export($this->combinations->toArray(), true)
            );
        }

        return $string;
    }

    /**
     * @return \DateInterval
     */
    private function getOneDayMore()
    {
        return new \DateInterval('P1D');
    }
}
