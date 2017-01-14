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
     * @param int $durationInDays
     * @param \Datetime $dateFrom
     * @param \Datetime $dateTo
     */
    public function __construct($durationInDays, \Datetime $dateFrom, \Datetime $dateTo)
    {
        $this->durationInDays = $durationInDays;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
     * @return ArrayCollection
     */
    public function generateDateCombinations()
    {
        $combinations = new ArrayCollection();
        $possibleDays = $this->dateFrom->diff($this->dateTo)->days;

        $initialDate = clone $this->dateFrom;
        $endDate = clone $this->dateFrom->add(new \DateInterval(sprintf('P%dD', $this->durationInDays)));

        for ($i = 0; $i < $possibleDays; $i++) {
            $combinations->add([
                'from' => clone $initialDate,
                'to' => clone $endDate
            ]);

            if ($endDate->diff($this->dateTo)->days == 0) {
                return $combinations;
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
     * @return \DateInterval
     */
    private function getOneDayMore() {
        return new \DateInterval('P1D');
    }
}
