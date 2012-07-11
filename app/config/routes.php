<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "main";

$route['404_override'] = 'main/error';


/*$route['workshops_and_trainings']    = 'main/getSystemTemplate/workshops_and_trainings';
$route['coaching']                   = 'main/getSystemTemplate/coaching';*/

//$route['page/consulting']            = 'main/getSystemTemplate/27';
$route['page/(:any)']                = 'main/getSystemTemplate/$1';

//$route['news/(:any)']                = 'main/getSystemTemplate/$1';

$route['template']                   = 'main/getSystemTemplate/template';
$route['books']                      = 'main/getUserCompositeTemplate/books';
$route['reviews']                    = 'main/getUserCompositeTemplate/reviews';

$route['news_item']                  = 'main/getUserCompositeTemplate/news_item';
$route['pages']                      = 'main/getUserCompositeTemplate/page';

$route['contact_us']                 = 'main/getUserCompositeTemplate/contact_us';
$route['news']                       = 'main/getUserCompositeTemplate/news';
$route['search']                       = 'main/getUserCompositeTemplate/search';

$route['about_company']                     = 'main/getSystemTemplate/about_company/1';
$route['useful']                            = 'main/getSystemTemplate/useful/1';
$route['practice']                          = 'main/getSystemTemplate/practice/1';
$route['our_clients']                       = 'main/getSystemTemplate/our_clients/1';



$route['page']                       = $route['news_item'];
$route['menu']                       = 'main/getUserCompositeTemplate/menu';


$route['login']                      = 'main/index/login';



