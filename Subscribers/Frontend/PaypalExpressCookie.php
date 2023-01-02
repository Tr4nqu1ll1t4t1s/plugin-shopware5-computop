<?php
/**
 * The Computop Shopware Plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Computop Shopware Plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Computop Shopware Plugin. If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.6, 7.0 , 7.1
 *
 * @category   Payment
 * @package    FatchipCTPayment
 * @subpackage Subscibers
 * @author     FATCHIP GmbH <support@fatchip.de>
 * @copyright  2018 Computop
 * @license    <http://www.gnu.org/licenses/> GNU Lesser General Public License
 * @link       https://www.computop.com
 */

namespace Shopware\Plugins\FatchipCTPayment\Subscribers\Frontend;

use Shopware\Plugins\FatchipCTPayment\Subscribers\AbstractSubscriber;
use Shopware\Models\Payment\Payment;
use Shopware\Bundle\CookieBundle\CookieCollection;
use Shopware\Bundle\CookieBundle\Structs\CookieStruct;
use Shopware\Bundle\CookieBundle\Structs\CookieGroupStruct;

class PaypalExpressCookie extends AbstractSubscriber
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (position defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     * <code>
     * return array(
     *     'eventName0' => 'callback0',
     *     'eventName1' => array('callback1'),
     *     'eventName2' => array('callback2', 10),
     *     'eventName3' => array(
     *         array('callback3_0', 5),
     *         array('callback3_1'),
     *         array('callback3_2')
     *     )
     * );
     *
     * </code>
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'CookieCollector_Collect_Cookies' => 'registerCTPaypalExpressCookie'
        ];
    }

    /**
     *
     * @return CookieCollection|void
     */
    public function registerCTPaypalExpressCookie()
    {
        if(class_exists('Shopware\\Bundle\\CookieBundle\\CookieCollection')) {
            /** @var PaymentRepository $paymentRepository */
            $paymentRepository = Shopware()->Models()->getRepository(Payment::class);

            /** @var Payment $payment */
            $payment = $paymentRepository->findOneBy(['name' => 'fatchip_computop_paypal_express']);
            if (!($payment and $payment->getActive())) {
                return;
            }

            $collection = new CookieCollection();
            $collection->add(new CookieStruct(
                'fatchipCTAmazon',
                '/^(ts_c | X-PP-ADS | rmuc | ddi | cookie_prefs | cookie_check | d_id | ts | enforce_policy | _ga | navlns | login_email | ui_experience | LANG | fn_dt | x-cdn | TLTSID | l7_az | id_token | KHcl0EuY7AKSMgfvHl7J5E7hPtK | tsrce | x-pp | Tv7XaFXkAfcLyjkmtYddHHs5nwS | -1ILhdyICORs4hS4xTUr41S8iP0 | Tv7XaFXkAfcLyjkmtYddHHs5nwS | feel_cookie | consumer_display | _gcl_au | x-csrf-jwt)/',
                'Computop Paypal Express Cookies',
                CookieGroupStruct::TECHNICAL
            ));

            return $collection;
        }

        return;
    }
}
