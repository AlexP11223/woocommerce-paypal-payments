<?php

namespace WooCommerce\PayPalCommerce\WcGateway\Gateway\PayUponInvoice;

use WC_Order;

class PaymentSourceFactory {

	public function from_wc_order( WC_Order $order ) {
		$address = $order->get_address();
		$birth_date = filter_input( INPUT_POST, 'billing_birth_date', FILTER_SANITIZE_STRING );
		$phone_country_code = WC()->countries->get_country_calling_code( $address['country'] ?? '' );

		$gateway_settings = get_option( 'woocommerce_ppcp-pay-upon-invoice-gateway_settings' );
		$merchant_name    = $gateway_settings['brand_name'] ?? '';
		$logo_url    = $gateway_settings['logo_url'] ?? '';
		$customer_service_instructions    = $gateway_settings['customer_service_instructions'] ?? '';

		return new PaymentSource(
			$address['first_name'] ?? '',
			$address['last_name'] ?? '',
			$address['email'] ?? '',
			$birth_date ?? '',
			$address['phone'] ?? '',
			substr($phone_country_code, strlen('+')) ?? '',
			$address['address_1'] ?? '',
			$address['city'] ?? '',
			$address['postcode'] ?? '',
			$address['country'] ?? '',
			'en-DE',
			$merchant_name,
			$logo_url,
			array($customer_service_instructions)
		);
	}
}