<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\DataTransfer;

class PeriodTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param Period $period
     * @param int $numberOfPossibilities
     * @param array $expected
     * @dataProvider dataProvider
     */
    public function testIfCombinationsAreGeneratedRight(Period $period, $numberOfPossibilities, array $expected) {
        $combinations = $period->generateDateCombinations();

        $this->assertEquals($numberOfPossibilities, count($combinations));
        $this->assertEquals($numberOfPossibilities, count($expected));

        $count = 0;
        foreach ($combinations as $combination) {
            /** @var \DateTime $expectedDateFrom */
            $expectedDateFrom = $expected[$count][0];
            /** @var \DateTime $expectedDateTo */
            $expectedDateTo = $expected[$count][1];

            $this->assertEquals($expectedDateFrom->format('Y-m-d'), $combination['outboundDate']);
            $this->assertEquals($expectedDateTo->format('Y-m-d'), $combination['inboundDate']);

            $count++;
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIfInvalidRangeThrowsInvalidArgumentException() {
        $period = new Period(20, new \DateTime('2017-08-01'), new \DateTime('2017-08-20'));
        $period->generateDateCombinations();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIfDurationInDaysZeroThrowsInvalidArgumentException() {
        new Period(0, new \DateTime('2017-08-01'), new \DateTime('2017-08-20'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIfNotANumberThrowsInvalidArgumentException() {
        new Period('1', new \DateTime('2017-08-01'), new \DateTime('2017-08-20'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIfNegativeDurationInDaysThrowsInvalidArgumentException() {
        new Period(-1, new \DateTime('2017-08-01'), new \DateTime('2017-08-20'));
    }

    /**
     */
    public function dataProvider() {
        return [
            [
                new Period(5, new \DateTime('2017-07-01'), new \DateTime('2017-07-07')),
                2,
                [
                    [new \DateTime('2017-07-01'), new \DateTime('2017-07-06')],
                    [new \DateTime('2017-07-02'), new \DateTime('2017-07-07')]
                ],
            ],
            [
                new Period(20, new \DateTime('2017-07-15'), new \DateTime('2017-08-28')),
                25,
                [
                    [new \DateTime('2017-07-15'), new \DateTime('2017-08-04')],
                    [new \DateTime('2017-07-16'), new \DateTime('2017-08-05')],
                    [new \DateTime('2017-07-17'), new \DateTime('2017-08-06')],
                    [new \DateTime('2017-07-18'), new \DateTime('2017-08-07')],
                    [new \DateTime('2017-07-19'), new \DateTime('2017-08-08')],
                    [new \DateTime('2017-07-20'), new \DateTime('2017-08-09')],
                    [new \DateTime('2017-07-21'), new \DateTime('2017-08-10')],
                    [new \DateTime('2017-07-22'), new \DateTime('2017-08-11')],
                    [new \DateTime('2017-07-23'), new \DateTime('2017-08-12')],
                    [new \DateTime('2017-07-24'), new \DateTime('2017-08-13')],
                    [new \DateTime('2017-07-25'), new \DateTime('2017-08-14')],
                    [new \DateTime('2017-07-26'), new \DateTime('2017-08-15')],
                    [new \DateTime('2017-07-27'), new \DateTime('2017-08-16')],
                    [new \DateTime('2017-07-28'), new \DateTime('2017-08-17')],
                    [new \DateTime('2017-07-29'), new \DateTime('2017-08-18')],
                    [new \DateTime('2017-07-30'), new \DateTime('2017-08-19')],
                    [new \DateTime('2017-07-31'), new \DateTime('2017-08-20')],
                    [new \DateTime('2017-08-01'), new \DateTime('2017-08-21')],
                    [new \DateTime('2017-08-02'), new \DateTime('2017-08-22')],
                    [new \DateTime('2017-08-03'), new \DateTime('2017-08-23')],
                    [new \DateTime('2017-08-04'), new \DateTime('2017-08-24')],
                    [new \DateTime('2017-08-05'), new \DateTime('2017-08-25')],
                    [new \DateTime('2017-08-06'), new \DateTime('2017-08-26')],
                    [new \DateTime('2017-08-07'), new \DateTime('2017-08-27')],
                    [new \DateTime('2017-08-08'), new \DateTime('2017-08-28')],
                ],
            ],
        ];
    }
}
