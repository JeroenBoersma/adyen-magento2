<?php
/**
 *                       ######
 *                       ######
 * ############    ####( ######  #####. ######  ############   ############
 * #############  #####( ######  #####. ######  #############  #############
 *        ######  #####( ######  #####. ######  #####  ######  #####  ######
 * ###### ######  #####( ######  #####. ######  #####  #####   #####  ######
 * ###### ######  #####( ######  #####. ######  #####          #####  ######
 * #############  #############  #############  #############  #####  ######
 *  ############   ############  #############   ############  #####  ######
 *                                      ######
 *                               #############
 *                               ############
 *
 * Adyen Payment module (https://www.adyen.com/)
 *
 * Copyright (c) 2019 Adyen BV (https://www.adyen.com/)
 * See LICENSE.txt for license details.
 *
 * Author: Adyen <magento@adyen.com>
 */

namespace Adyen\Payment\Gateway\Response;

use Adyen\Payment\Helper\Data;
use Adyen\Payment\Helper\Recurring;
use Adyen\Payment\Helper\Vault;
use Magento\Payment\Gateway\Data\PaymentDataObject;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;

class VaultDetailsHandler implements HandlerInterface
{
    /**
     * @var Vault
     */
    private $vaultHelper;

    /**
     * @var Data
     */
    private $adyenHelper;

    /**
     * @var Recurring
     */
    private $recurringHelper;

    /**
     * VaultDetailsHandler constructor.
     *
     * @param Vault $vaultHelper
     * @param Data $adyenHelper
     * @param Recurring $recurringHelper
     */
    public function __construct(Vault $vaultHelper, Data $adyenHelper, Recurring $recurringHelper)
    {
        $this->vaultHelper = $vaultHelper;
        $this->adyenHelper = $adyenHelper;
        $this->recurringHelper = $recurringHelper;
    }

    /**
     * @inheritdoc
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (empty($response['additionalData'])) {
            return;
        }
        /** @var PaymentDataObject $orderPayment */
        $orderPayment = SubjectReader::readPayment($handlingSubject);

        if ($this->recurringHelper->isCreditCardVaultEnabled()) {
            $this->vaultHelper->saveRecurringDetails($orderPayment->getPayment(), $response['additionalData']);
        }
    }
}
