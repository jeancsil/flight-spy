<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Notifier\Email;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;
use Jeancsil\FlightSpy\Service\Currency\PriceFormatter;
use Jeancsil\FlightSpy\Service\ElasticSearch\ElasticSearchWriterTrait;
use Jeancsil\FlightSpy\Service\ElasticSearch\ElasticSearchRequester;
use Jeancsil\FlightSpy\Notifier\Deal;
use Jeancsil\FlightSpy\Notifier\NotifiableInterface;
use Postmark\PostmarkClient;

class Notifier implements NotifiableInterface
{
    use ElasticSearchWriterTrait;

    /**
     * @var ElasticSearchRequester
     */
    private $elasticSearchRequester;
    /**
     * @var PriceFormatter
     */
    private $priceFormatter;
    /**
     * @var PostmarkClient
     */
    private $mailer;
    private $html;
    private $from;
    private $to;
    private $subject;

    private $tableLines = [];

    public function __construct(PostmarkClient $mailer, $html, $from, $to, $subject)
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;

        $this->initializeHtmlTemplate($html);
    }

    /** @inheritdoc */
    public function notify(array $deals, SessionParameters $sessionParameters)
    {
        $notifications = $this->createNotifications($sessionParameters, $deals);

        if (empty($this->tableLines)) {
            return;
        }

        /**
         * @var string $identifier
         * @var EmailNotification $notification
         */
        foreach ($notifications as $identifier => $notification) {
            $this->elasticSearchWriter
                ->writeOne([
                    'identifier' => $identifier,
                    'notified' => $this->to
                ]);
        }

        $this->mailer->sendEmail(
            $this->from,
            $this->to,
            $this->subject,
            str_replace('<!--NewLine-->', implode('', $this->tableLines), $this->html)
        );
    }

    /** @inheritdoc */
    public function wasNotified(Deal $deal, $notifyTo)
    {
        return $this->elasticSearchRequester
            ->wasNotified(
                $deal->getIdentifier(),
                $notifyTo
            );
    }


    /** @inheritdoc */
    public function createNotifications(SessionParameters $parameters, array $deals = [])
    {
        $notifications = [];
        /** @var Deal $deal */
        foreach ($deals as $deal) {
            if ($this->wasNotified($deal, $this->to)) {
                continue;
            }

            $this->tableLines[] = $this->createTableLine(
                $deal->getAgentName(),
                $this->priceFormatter->format($deal->getPrice(), $parameters->currency),
                $deal->getDeepLinkUrl()
            );

            $notifications[$deal->getIdentifier()] = new EmailNotification();
        }

        return $notifications;
    }

    /**
     * @param ElasticSearchRequester $elasticSearchRequester
     */
    public function setElasticSearchRequester(ElasticSearchRequester $elasticSearchRequester)
    {
        $this->elasticSearchRequester = $elasticSearchRequester;
    }

    /**
     * @param PriceFormatter $priceFormatter
     */
    public function setPriceFormatter(PriceFormatter $priceFormatter)
    {
        $this->priceFormatter = $priceFormatter;
    }

    /**
     * @param $htmlTemplate
     */
    private function initializeHtmlTemplate($htmlTemplate)
    {
        $this->html = file_get_contents($htmlTemplate);
    }

    /**
     * @param $agentName
     * @param $price
     * @param $deepLink
     * @return string
     */
    private function createTableLine($agentName, $price, $deepLink)
    {
        $deepLinkHtml = '';

        if ($deepLink) {
            $deepLinkHtml = '<a href="' . $deepLink . '" 
               target="_blank" 
               style="text-decoration:underline;background-color:#ffffff;
                border:solid 1px #3498db;border-radius:5px;
                box-sizing:border-box;color:#3498db;
                cursor:pointer;font-size:12px;
                margin:0;padding:3px 3px;
                text-decoration:none;text-transform:capitalize;
                background-color:#3498db;border-color:#3498db;color:#ffffff;">Book</a>';
        }

        return sprintf(
            '<tr>
                <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;">%s</td>
                <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;">%s</td>
                <td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;">%s</td>
            </tr>',
            $agentName,
            $price,
            $deepLinkHtml
        );
    }
}
