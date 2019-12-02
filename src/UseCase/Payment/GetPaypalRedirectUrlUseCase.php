<?php
declare(strict_types=1);

namespace App\UseCase\Payment;

use App\Model\PaymentModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetPaypalRedirectUrlUseCase
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param PaymentModel $payment
     * @param string $cancelUrl
     * @param string $notifyUrl
     * @return string
     */
    public function getUrl(PaymentModel $payment): string
    {
        $redirectURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        $redirectURL2 = 'https://www.paypal.com/cgi-bin/webscr';

        $cancelUrl = $this->urlGenerator->generate('account_index', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $returnUrl = $this->urlGenerator->generate('account_index', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $notifyUrl = $this->urlGenerator->generate('finish_payment', ['thank_you' => true], UrlGeneratorInterface::ABSOLUTE_URL);

        $redirectURL .= "?business=".urlencode('sb-mbg474630020@business.example.com')."&";
        $redirectURL .= "item_name=Paid for services (".urlencode($payment->getProductId()).")&";
        $redirectURL .= "amount=".urlencode((string)$payment->getTotalPrice())."&";
        $redirectURL .= "custom=".urlencode($payment->getProductId())."&";
        $redirectURL .= "currency_code=USD&";
        $redirectURL .= "cmd=" . urlencode('_xclick') . "&";
        $redirectURL .= "rm=" . urlencode('2') . "&";
        $redirectURL .= "no_note=" . urlencode('1') . "&";
        $redirectURL .= "bn=" . urlencode('PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest') . "&";
        $redirectURL .= "return=".urlencode($notifyUrl)."&";
        $redirectURL .= "cancel_return=".urlencode($cancelUrl)."&";
        $redirectURL .= "notify_url=".urlencode($notifyUrl);

        return $redirectURL;
    }

}
