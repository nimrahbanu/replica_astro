<?php
/**
 * 2007-2016 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    PagSeguro Internet Ltda.
 * @copyright 2007-2016 PagSeguro Internet Ltda.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 *
 */

namespace PagSeguro\Parsers\DirectPayment\OnlineDebit;

/**
 * Request from the Online debit direct payment
 * @package PagSeguro\Parsers\DirectPayment\OnlineDebit
 */

use PagSeguro\Enum\Properties\Current;
use PagSeguro\Parsers\Basic;
use PagSeguro\Parsers\Currency;
use PagSeguro\Parsers\DirectPayment\Mode;
use PagSeguro\Parsers\Error;
use PagSeguro\Parsers\Item;
use PagSeguro\Parsers\Parameter;
use PagSeguro\Parsers\Parser;
use PagSeguro\Parsers\ReceiverEmail;
use PagSeguro\Parsers\Sender;
use PagSeguro\Parsers\Shipping;
use PagSeguro\Parsers\Transaction\OnlineDebit\Response;
use PagSeguro\Resources\Http;

/**
 * Class Request
 * @package PagSeguro\Parsers\DirectPayment\OnlineDebit
 */
class Request extends Error implements Parser
{
    use BankName;
    use Basic;
    use Currency;
    use Item;
    use Method;
    use Mode;
    use Parameter;
    use ReceiverEmail;
    use Sender;
    use Shipping;

    /**
     * @param \PagSeguro\Domains\Requests\DirectPayment\OnlineDebit $onlineDebit
     * @return array
     */
    public static function getData(\PagSeguro\Domains\Requests\DirectPayment\OnlineDebit $onlineDebit)
    {
        $data = [];
        $properties = new Current();
        return array_merge(
            $data,
            BankName::getData($onlineDebit, $properties),
            Basic::getData($onlineDebit, $properties),
            Currency::getData($onlineDebit, $properties),
            Item::getData($onlineDebit, $properties),
            Method::getData($properties),
            Mode::getData($onlineDebit, $properties),
            Parameter::getData($onlineDebit),
            ReceiverEmail::getData($onlineDebit, $properties),
            Sender::getData($onlineDebit, $properties),
            Shipping::getData($onlineDebit, $properties)
        );
    }

    /**
     * @param Http $http
     * @return mixed
     */
    public static function success(Http $http)
    {
        $xml = simplexml_load_string($http->getResponse());

        return (new Response)->setDate(lw_current_func($xml->date))
            ->setCode(lw_current_func($xml->code))
            ->setReference(lw_current_func($xml->reference))
            ->setRecoveryCode(lw_current_func($xml->recoveryCode))
            ->setType(lw_current_func($xml->type))
            ->setStatus(lw_current_func($xml->status))
            ->setLastEventDate(lw_current_func($xml->lastEventDate))
            ->setCancelationSource(lw_current_func($xml->cancelationSource))
            ->setPaymentLink(lw_current_func($xml->paymentLink))
            ->setPaymentMethod($xml->paymentMethod)
            ->setGrossAmount(lw_current_func($xml->grossAmount))
            ->setDiscountAmount(lw_current_func($xml->discountAmount))
            ->setFeeAmount(lw_current_func($xml->feeAmount))
            ->setNetAmount(lw_current_func($xml->netAmount))
            ->setExtraAmount(lw_current_func($xml->extraAmount))
            ->setEscrowEndDate(lw_current_func($xml->escrowEndDate))
            ->setInstallmentCount(lw_current_func($xml->installmentCount))
            ->setItemCount(lw_current_func($xml->itemCount))
            ->setItems($xml->items)
            ->setSender($xml->sender)
            ->setCreditorFees($xml->creditorFees)
            ->setApplication($xml->applications)
            ->setShipping($xml->shipping);
    }

    /**
     * @param Http $http
     * @return mixed|\PagSeguro\Domains\Error
     */
    public static function error(Http $http)
    {
        return parent::error($http);
    }
}
