<?php
/**
 * The message repository.
 *
 * @package Inpsyde\PayPalCommerce\AdminNotices\Repository
 */

declare(strict_types=1);

namespace Inpsyde\PayPalCommerce\AdminNotices\Repository;

use Inpsyde\PayPalCommerce\AdminNotices\Entity\Message;

/**
 * Class Repository
 */
class Repository implements RepositoryInterface {

	const NOTICES_FILTER = 'ppcp.admin-notices.current-notices';

	/**
	 * Returns the current messages.
	 *
	 * @return Message[]
	 */
	public function current_message(): array {
		return array_filter(
			(array) apply_filters(
				self::NOTICES_FILTER,
				array()
			),
			function( $element ) : bool {
				return is_a( $element, Message::class );
			}
		);
	}
}
