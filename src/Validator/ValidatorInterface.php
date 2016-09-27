<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Validator;

interface ValidatorInterface
{
    /**
     * @param $instance
     * @return $this
     */
    public function setInstance($instance);

    /**
     * @throw ValidationException
     */
    public function validate();
}
