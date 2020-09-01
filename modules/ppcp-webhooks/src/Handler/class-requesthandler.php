<?php
/**
 * The interface for the request handlers.
 *
 * @package Inpsyde\PayPalCommerce\Webhooks\Handler
 */

declare(strict_types=1);

namespace Inpsyde\PayPalCommerce\Webhooks\Handler;

/**
 * Interface RequestHandler
 */
interface RequestHandler {

	/**
	 * The event types a handler handles.
	 *
	 * @return array
	 */
	public function event_types(): array;

	/**
	 * Whether a handler is responsible for a given request or not.
	 *
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return bool
	 */
	public function responsible_for_request( \WP_REST_Request $request): bool;

	/**
	 * Responsible for handling the request.
	 *
	 * @param \WP_REST_Request $request The request.
	 *
	 * @return \WP_REST_Response
	 */
	public function handle_request( \WP_REST_Request $request): \WP_REST_Response;
}
