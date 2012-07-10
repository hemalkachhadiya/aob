<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "main";

$route['404_override'] = 'main/error';


/*$route['workshops_and_trainings']    = 'main/getSystemTemplate/workshops_and_trainings';
$route['coaching']                   = 'main/getSystemTemplate/coaching';*/

$route['page/consulting']            = 'main/index/consulting';
$route['page/(:any)']                = 'main/getSystemTemplate/$1';
//$route['news/(:any)']                = 'main/getSystemTemplate/$1';

$route['template']                   = 'main/getSystemTemplate/template';
$route['books']                      = 'main/getUserCompositeTemplate/books';
$route['reviews']                    = 'main/getUserCompositeTemplate/reviews';
$route['news']                       = 'main/getUserCompositeTemplate/news';
$route['news_item']                  = 'main/getUserCompositeTemplate/news_item';

$route['contact_us']                 = 'main/getUserCompositeTemplate/contact_us';



$route['login']                      = 'main/index/login';



