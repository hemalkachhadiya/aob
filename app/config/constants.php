<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define ('DASHBOARD_COMMENT_DISPLAY_AMOUNT',5);
define ('WIDGET_AMOUNT',5);
define ('SEARCH_RESULTS',10);
define ('SHOP_SEARCH_RESULTS',9);
define ('CARROUSEL_RESULTS',4);
define ('NEWS_RESULTS',2);
define ('FREE_WEEK_OFFER_AMOUNT',10);
define ('FREE_WEEK_PROJECT_AMOUNT',5);


define ('LETTERS_AMOUNT',10);


define('TEMPLATE_PATH','/img/templates/');
define('GROUP_DEFAULT_PHOTO',TEMPLATE_PATH.'n_group.png');
define('USER_DEFAULT_PHOTO',TEMPLATE_PATH.'no-photo.jpg');
define('PORTFOLIO_DEFAULT_PHOTO',TEMPLATE_PATH.'no-preview.jpg');
define('PRODUCT_DEFAULT_PHOTO',TEMPLATE_PATH.'n_product.png');



define('DeliveryBoth',1);
define('DeliveryEmail',2);
define('DeliveryMail',3);
define('CAPTCHA_PUBLIC_KEY','6LfuXtESAAAAAAavNeF9b53MkxK-Di868ZaH5ZZh');
define('CAPTCHA_PRIVATE_KEY','6LfuXtESAAAAAMeJKPiJCR3ojfw9S_xDSrNYWuaF');
define('DEFENSIO_API_KEY','4ffc3edace7ca90d4276d0d464d58d5c');
define('CarrouselPageAmount',5);

define('TYPE_REFERAT',1);
define('TYPE_DIPLOM',1);
define('TYPE_KURSOVAIA',1);

define('PAYMENT_TYPE_RELATIVE',1);
define('PAYMENT_TYPE_ABSOLUTE',2);