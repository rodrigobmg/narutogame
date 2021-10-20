<?php
/**
 * Opauth basic configuration file to quickly get you started
 * ==========================================================
 * To use: rename to opauth.conf.php and tweak as you like
 * If you require advanced configuration options, refer to opauth.conf.php.advanced
 */

$config = array(
/**
 * Path where Opauth is accessed.
 *  - Begins and ends with /
 *  - eg. if Opauth is reached via http://example.org/auth/, path is '/auth/'
 *  - if Opauth is reached via http://auth.example.org/, path is '/'
 */
	'path' => '/oauth/?auth_result=',

/**
 * Callback URL: redirected to after authentication, successful or otherwise
 */
	'callback_url' => '/oauth/?callback',
	
/**
 * A random string used for signing of $auth response.
 * 
 * NOTE: PLEASE CHANGE THIS INTO SOME OTHER RANDOM STRING
 */
	'security_salt' => 'cjr79RqPmsgH6f6uJj81RB57ReB066L6wo80w2urd28bGhXmH10724x2XSR8mVL7fD5i5HO78W496ic4YK1C7QD47p71T7X373Hg',
		
/**
 * Strategy
 * Refer to individual strategy's documentation on configuration requirements.
 * 
 * eg.
 * 'Strategy' => array(
 * 
 *   'Facebook' => array(
 *      'app_id' => 'APP ID',
 *      'app_secret' => 'APP_SECRET'
 *    ),
 * 
 * )
 *
 */
	'Strategy' => array(
		'Facebook' => array(
			'app_id' => '278794155478934',
			'app_secret' => '1f5ffa715e6bd42839fe2378b1154b4c'
		)
	),
);