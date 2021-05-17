import {useState, useEffect} from '@wordpress/element';
import {registerPaymentMethod} from '@woocommerce/blocks-registry';
import {getSettings, initStripe} from "../util";
import {LocalPaymentIntentContent} from './local-payment-method';
import {PaymentMethod} from "../../components/checkout";
import {canMakePayment} from "./local-payment-method";
import {AfterpayClearpayMessageElement, Elements} from "@stripe/react-stripe-js";
import {sprintf, __} from '@wordpress/i18n';

const getData = getSettings('stripe_afterpay_data');
let variablesHandler;
const setVariablesHandler = (handler) => {
    variablesHandler = handler;
}
const PaymentMethodLabel = ({getData}) => {
    const [variables, setVariables] = useState({
        amount: getData('cartTotal'),
        currency: getData('currency')
    });
    setVariablesHandler(setVariables);
    return (
        <Elements stripe={initStripe}>
            <div className='wc-stripe-blocks-afterpay__label'>
                <AfterpayClearpayMessageElement options={{
                    ...getData('msgOptions'),
                    ...{
                        amount: variables.amount,
                        currency: variables.currency,
                    }
                }}/>
            </div>
        </Elements>
    );
}

const PaymentMethodTest = ({content, billing, shippingData, ...props}) => {
    const Content = content;
    const {cartTotal, currency} = billing;
    const {needsShipping} = shippingData
    useEffect(() => {
        setVariablesHandler({
            amount: cartTotal.value,
            currency: currency.code,
            isEligible: needsShipping
        });
    }, [
        cartTotal.value,
        currency.code,
        needsShipping
    ]);
    return (
        <>
            {needsShipping &&
            <div className='wc-stripe-blocks-payment-method-content'>
                <div className="wc-stripe-blocks-afterpay-offsite__container">
                    <div className="wc-stripe-blocks-afterpay__offsite">
                        <img src={getData('offSiteSrc')}/>
                        <p>{sprintf(__('After clicking "%s", you will be redirected to Afterpay to complete your purchase securely.', 'woo-stripe-payment'), getData('placeOrderButtonLabel'))}</p>
                    </div>
                </div>
                <Content {...{...props, billing, shippingData}}/>
            </div>}
        </>
    );
}

if (getData()) {
    registerPaymentMethod({
        name: getData('name'),
        label: <PaymentMethodLabel
            getData={getData}/>,
        ariaLabel: 'Ideal',
        placeOrderButtonLabel: getData('placeOrderButtonLabel'),
        canMakePayment: canMakePayment(getData, ({settings, billingData, cartTotals}) => {
            const {country} = billingData;
            const {currency_code: currency} = cartTotals;
            const requiredParams = settings('requiredParams');
            const [countryCode] = requiredParams[currency];
            return country == countryCode;
        }),
        content: <PaymentMethodTest
            content={LocalPaymentIntentContent}
            getData={getData}
            confirmationMethod={'confirmAfterpayClearpayPayment'}/>,
        edit: <PaymentMethod content={LocalPaymentIntentContent} getData={getData}/>,
        supports: {
            showSavedCards: false,
            showSaveOption: false,
            features: getData('features')
        }
    })
}