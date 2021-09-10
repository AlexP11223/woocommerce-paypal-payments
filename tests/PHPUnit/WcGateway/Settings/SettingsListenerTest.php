<?php

namespace WooCommerce\PayPalCommerce\WcGateway\Settings;

use WooCommerce\PayPalCommerce\ApiClient\Authentication\Bearer;
use WooCommerce\PayPalCommerce\ApiClient\Helper\Cache;
use WooCommerce\PayPalCommerce\ModularTestCase;
use WooCommerce\PayPalCommerce\Onboarding\State;
use Mockery;
use WooCommerce\PayPalCommerce\WcGateway\Gateway\PayPalGateway;
use WooCommerce\PayPalCommerce\Webhooks\WebhookRegistrar;
use function Brain\Monkey\Functions\when;

class SettingsListenerTest extends ModularTestCase
{
	private $appContainer;

	public function setUp(): void
	{
		parent::setUp();

		$this->appContainer = $this->bootstrapModule();
	}

	public function testListen()
	{
		$settings = Mockery::mock(Settings::class);
		$settings->shouldReceive('set');

		$setting_fields = $this->appContainer->get('wcgateway.settings.fields');

		$webhook_registrar = Mockery::mock(WebhookRegistrar::class);
		$webhook_registrar->shouldReceive('unregister')->andReturnTrue();
		$webhook_registrar->shouldReceive('register')->andReturnTrue();

		$cache = Mockery::mock(Cache::class);

		$state = Mockery::mock(State::class);
		$state->shouldReceive('current_state')->andReturn(State::STATE_ONBOARDED);
		$bearer = Mockery::mock(Bearer::class);

		$testee = new SettingsListener(
			$settings,
			$setting_fields,
			$webhook_registrar,
			$cache,
			$state,
			$bearer,
			PayPalGateway::ID
		);

		$_GET['section'] = PayPalGateway::ID;
		$_POST['ppcp-nonce'] = 'foo';
		$_POST['ppcp'] = [
			'client_id' => 'client_id',
		];
		$_GET['ppcp-tab'] = PayPalGateway::ID;

		when('current_user_can')->justReturn(true);
		when('wp_verify_nonce')->justReturn(true);

		$settings->shouldReceive('has')
			->with('client_id')
			->andReturn('client_id');
		$settings->shouldReceive('get')
			->with('client_id')
			->andReturn('client_id');
		$settings->shouldReceive('has')
			->with('client_secret')
			->andReturn('client_secret');
		$settings->shouldReceive('get')
			->with('client_secret')
			->andReturn('client_secret');
		$settings->shouldReceive('persist');
		$cache->shouldReceive('has')
			->andReturn(false);

		$testee->listen();
	}
}
