<?php
session_start();
ini_set('display_errors',0);
ini_set("max_execution_time",1800);
ini_set("memory_limit", "-1");
ini_set('session.cookie_httponly',1);
date_default_timezone_set('America/Mexico_City');
mb_internal_encoding("UTF-8");

//##### GENERAL CONSTANTS #####
if(!defined('ENVIRONMENT'))         define('ENVIRONMENT','DEV'); // DEV=DEVELOPMENT, PROD=PRODUCTION
if(!defined('PATH_APP'))            define('PATH_APP',$_SERVER["DOCUMENT_ROOT"]);
if(!defined('CSS_PATH'))            define('CSS_PATH',PATH_APP.'/css/');
if(!defined('IMAGE_PATH'))          define('IMAGE_PATH',PATH_APP.'/images/');
if(!defined('PART_IMAGE_PATH'))     define('PART_IMAGE_PATH',PATH_APP.'/images/parts/');
if(!defined('INCLUDE_PATH'))        define('INCLUDE_PATH',PATH_APP.'/include/');
if(!defined('JS_PATH'))             define('JS_PATH',PATH_APP.'/js/');
if(!defined('LIB_PATH'))            define('LIB_PATH',PATH_APP.'/lib/');
if(!defined('MODULE_PATH'))         define('MODULE_PATH',PATH_APP.'/modules/');
if(!defined('DEBUG'))               define('DEBUG', true);

//##### DB CONSTANTS #####
if(!defined('DB_USER'))             define('DB_USER','scrap');
if(!defined('DB_USER_PASS'))        define('DB_USER_PASS','Scrap8956');
if(!defined('DB_SERVER'))           define('DB_SERVER','localhost/XE');
if(!defined('DB_CHARSET'))          define('DB_CHARSET','UTF8');

//##### WS CONSTANTS #####
if(!defined('SOAP_URL'))            define('SOAP_URL','http://webapps3.cw01.contiwan.com:8080/UserAuthenticateSAPLDAP/UserAuthenticate.svc?singleWsdl');

//##### AD CONSTANTS #####
if(!defined('AD_SERVER_PRIMARY'))   define('AD_SERVER_PRIMARY','tq2c101a.cw01.contiwan.com');
if(!defined('AD_SERVER_SECONDARY')) define('AD_SERVER_SECONDARY','gl2c101a.cw01.contiwan.com');
if(!defined('AD_USER_DOMAIN'))      define('AD_USER_DOMAIN','@cw01.contiwan.com');
?>