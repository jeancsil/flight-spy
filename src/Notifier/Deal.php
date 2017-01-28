<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;

class Deal
{
    /**
     * @var SessionParameters
     */
    private $sessionParameters;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $agentName;

    /**
     * @var string
     */
    private $deepLinkUrl;

    public function __construct(SessionParameters $sessionParameters, $price, $agentName, $deepLinkUrl)
    {
        $this->sessionParameters = $sessionParameters;
        $this->price = $price;
        $this->agentName = $agentName;
        $this->deepLinkUrl = $deepLinkUrl;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getAgentName()
    {
        return $this->agentName;
    }

    /**
     * @return string
     */
    public function getDeepLinkUrl()
    {
        return $this->deepLinkUrl;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return md5(
            sprintf(
                '%s%s%s',
                $this->agentName,
                $this->price,
                $this->sessionParameters
            )
        );
    }
}
