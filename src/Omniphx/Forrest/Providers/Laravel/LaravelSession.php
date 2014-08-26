<?php namespace Omniphx\Forrest\Providers\Laravel;

use Omniphx\Forrest\Interfaces\SessionInterface;
use Omniphx\Forrest\Exceptions\MissingTokenException;
use Omniphx\Forrest\Exceptions\MissingKeyException;
use Illuminate\Hashing\HasherInterface;
use Session;
use Crypt;

class LaravelSession implements SessionInterface {

	public function get($key)
	{
		$value = \Cache::get($key);
		if (isset($value)) {
			return \Cache::get($key);
		}

		throw new MissingKeyException(sprintf("No value for requested key: %s",$key));
	}

	public function put($key, $value)
	{
		return \Cache::put($key, $value, 10);
	}

	public function putToken($token)
	{
		$encyptedToken = Crypt::encrypt($token);
		return \Cache::put('forrest_token', $encyptedToken, 10);
	}

	public function getToken(){
		$token = \Cache::get('forrest_token');
		if (isset($token)) {
			return Crypt::decrypt($token);
		}

		throw new MissingTokenException(sprintf('No token available in current Session'));
	}

	public function putRefreshToken($token)
	{
		$encyptedToken = Crypt::encrypt($token);
		return \Cache::put('refresh_token', $encyptedToken, 10);
	}

	public function getRefreshToken()
	{
		$token = \Cache::get('refresh_token');
		if (isset($token)) {
			return Crypt::decrypt($token);
		}

		throw new MissingTokenException(sprintf('No refresh token available in current Session'));
	}
}