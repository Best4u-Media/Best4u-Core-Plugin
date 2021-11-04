<?php

namespace Best4u\Core\Common\Utils;

use Best4u\Core\Common\Utils\DebugLogger;

class UserIdentifier
{
	public static function userIsBest4u($user)
	{
		if (!$user instanceof \WP_User) {
			if (is_int($user)) {
				$user = new \WP_User($user);
			} else {
				return false;
			}
		}

		return $user->user_login === 'best4u' ||
			strpos($user->user_email, '@best4u.nl') !== false;
	}

	public static function currentUserIsBest4u()
	{
		return static::userIsBest4u(wp_get_current_user());
	}
}
