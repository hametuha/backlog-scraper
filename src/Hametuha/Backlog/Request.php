<?php

namespace Hametuha\Backlog;

/**
 * Send request to Backlog
 *
 * @package Hametuha\Backlog
 * @method \stdClass|array get( $endpoint, array $params = [ ] )
 * @method \stdClass|array delete( $endpoint )
 * @method \stdClass|array post( $endpoint, array $params = [ ] )
 * @method \stdClass|array put( $endpoint, array $params = [ ] )
 * @method \stdClass|array patch( $endpoint, array $params = [ ] )
 */
class Request {

	private $config = [];

	/**
	 * Request constructor.
	 *
	 * @param string|array $config
	 */
	public function __construct( $config ) {
		if ( is_string( $config ) ) {
			// Check if specified path is file
			if ( ! file_exists( $config ) ) {
				return;
			}
			$config = json_decode( file_get_contents( $config ), true );
			if ( $config ) {
				$this->config = $config;
			}
		} elseif ( is_array( $config ) ) {
			$this->config = $config;
		}
	}

	/**
	 * Send request.
	 *
	 * @param string $method One of GET, PUT, POST, PATCH, DELETE.
	 * @param string $endpoint Request endpoint.
	 * @param array $params parameters.
	 *
	 * @return \stdClass
	 * @throws \Exception
	 */
	protected function send( $method, $endpoint, array $params = [] ) {
		// Test config
		$unset = [];
		foreach ( [ 'apiKey', 'base' ] as $required ) {
			if ( ! isset( $this->config[ $required ] ) ) {
				$unset[] = $required;
			}
		}
		if ( $unset ) {
			throw new \Exception( sprintf( 'Required parameters are not set: %s', implode( ',', $unset ) ), 500 );
		}
		// Add API query.
		// Build URL
		$url      = ltrim( $endpoint, '/' );
		$base     = rtrim( $this->config['base'], '/' );
		$endpoint = "{$base}/{$url}";
		// Initialize cURL
		$ch = curl_init();
		curl_setopt_array( $ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_TIMEOUT        => 5,
			CURLOPT_FAILONERROR    => false,
		] );
		// Build endpoint
		switch ( $method ) {
			case 'POST';
			case 'PATCH':
			case 'PUT':
			case 'DELETE':
				$endpoint = "{$endpoint}?apiKey={$this->config['apiKey']}";
				break;
			case 'GET':
			default:
				if ( ! isset( $params['apiKey'] ) ) {
					$params['apiKey'] = $this->config['apiKey'];
				}
				$endpoint = $endpoint . '?' . http_build_query( $params );
				break;
		};
		// Set parameters.
		switch ( $method ) {
			case 'POST':
				curl_setopt( $ch, CURLOPT_POST, true );
				break;
			case 'PATCH':
			case 'PUT':
			case 'DELETE':
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
				break;
			case 'GET':
			default:
				// Do nothing
				break;
		}
		// Setup data.
		switch ( $method ) {
			case 'POST':
			case 'PATCH':
			case 'PUT':
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
				break;
			default:
				// Do nothing
				break;

		}
		// Set URL.
		curl_setopt( $ch, CURLOPT_URL, $endpoint );
		// Do request
		$body = curl_exec( $ch );
		if ( CURLE_OK !== ( $no = curl_errno( $ch ) ) ) {
			$msg = curl_error( $ch );
			if ( $body ) {
				$msg = $body;
			}
			curl_close( $ch );
			throw new \Exception( $body, $no );
		}
		curl_close( $ch );

		return json_decode( $body );
	}

	/**
	 * Magic method.
	 *
	 * @param string $name
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public function __call( $name, $arguments ) {
		switch ( $name ) {
			case 'get':
			case 'delete':
			case 'put':
			case 'post':
			case 'patch':
				$method = strtoupper( $name );
				array_unshift( $arguments, $method );

				return call_user_func_array( [ $this, 'send' ], $arguments );
				break;
			default:
				return null;
				break;
		}
	}


}
