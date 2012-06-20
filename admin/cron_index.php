<?php

// Configuration
require_once('config.php');

// Exit if not CLI initiated
if (PHP_SAPI !== 'cli') {
	require_once('index.php');
	exit(0);
}


// CLI Initiated
define('CLI_INITIATED', true);

if ($argc > 1 && $argv[1]) {
	define('PROFILE_NAME', $argv[1]);
} else {
	define ('PROFILE_NAME', 'default');
}

// Version
define('VERSION', '1.5.1');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);
		
// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
 
foreach ($query->rows as $setting) {
	$config->set($setting['key'], $setting['value']);
}

// Url
$url = new Url(HTTP_SERVER, HTTPS_SERVER);	
$registry->set('url', $url);
		
// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);
		
// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response); 

// Cache
$cache = new Cache();
$registry->set('cache', $cache); 

// Session
$session = new Session();
$registry->set('session', $session); 

// Language
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language	
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);	
$registry->set('language', $language); 		

// Document
$document = new Document();
$registry->set('document', $document); 		
						
// Front Controller
$controller = new Front($registry);

// Router
$action = new Action('tool/total_import/step1');

$controller->dispatch($action, new Action('error/not_found'));
?>
