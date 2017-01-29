<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Log;

use Monolog\Formatter\LineFormatter;

class MultiLineFormatter extends LineFormatter
{
    public function __construct()
    {
        parent::__construct(null, null, true);
    }
}
