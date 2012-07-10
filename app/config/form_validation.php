<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'editTemplate' => array(
		array('field' => 'title', 'label' => 'Заголовок', 'rules' => 'trim|required|max_length[256]'),
		array('field' => 'body', 'label' => 'Тело', 'rules' => 'trim|required')
    ),
    'createNewsItem' => array(
		array('field' => 'title', 'label' => 'Заголовок', 'rules' => 'trim|required|max_length[256]'),
		array('field' => 'description', 'label' => 'Описание', 'rules' => 'trim|required|max_length[256]')
    ),
	'editProfile' => array(
		array('field' => 'firstName', 'label' => 'Имя', 'rules' => 'trim|required|htmlspecialchars|max_length[64]|callback_checkDepartmentsAmount'),
        array('field' => 'nickname', 'label' => 'Никнейм', 'rules' => 'trim|required|htmlspecialchars|max_length[512]|callback_checkNickname|regex_match[/^[a-zA-Z0-9]*$/]'),
		array('field' => 'lastName', 'label' => 'Фамилия', 'rules' => 'trim|required|htmlspecialchars|max_length[64]'),
        array('field' => 'sex', 'label' => 'Пол', 'rules' => 'trim|required|htmlspecialchars|max_length[64]'),
        array('field' => 'country', 'label' => 'Страна', 'rules' => 'trim|required|htmlspecialchars|max_length[64]'),
        array('field' => 'city', 'label' => 'Город', 'rules' => 'trim|required|htmlspecialchars|max_length[64]'),
        array('field' => 'description', 'label' => 'Описание', 'rules' => 'trim|required|htmlspecialchars|max_length[1024]'),
        array('field' => 'skype', 'label' => 'skype', 'rules' => 'trim|htmlspecialchars|max_length[64]'),
        array('field' => 'website', 'label' => 'сайт', 'rules' => 'trim|htmlspecialchars|max_length[64]'),
        array('field' => 'icq', 'label' => 'icq', 'rules' => 'trim|htmlspecialchars|max_length[64]'),

        array('field' => 'password_change2', 'label' => 'Пароль повторно', 'rules' => 'trim|htmlspecialchars'),
        array('field' => 'password_change', 'label' => 'Пароль', 'rules' => 'trim|htmlspecialchars|matches[password_change2]'),

/*		array('field' => 'email', 'label' => 'email', 'rules' => 'trim|required|valid_email|max_length[128]'),
		array('field' => 'address[street]', 'label' => 'street address', 'rules' => 'trim|required|htmlspecialchars|max_length[128]'),
		array('field' => 'address[city]', 'label' => 'city', 'rules' => 'trim|required|htmlspecialchars|max_length[128]'),
		array('field' => 'address[state]', 'label' => 'state', 'rules' => 'trim|required|htmlspecialchars'),
		array('field' => 'address[zip]', 'label' => 'zip code', 'rules' => 'trim|required|regex_match[/^\d{5}([\-]\d{4})?$/]'),
		array('field' => 'phone[code]', 'label' => 'area code', 'rules' => 'trim|required|numeric|exact_length[3]'),
		array('field' => 'phone[num]', 'label' => 'phone number', 'rules' => 'trim|required|numeric|min_length[7]|max_length[8]')*/
	),
    'addReview' => array(
        array('field' => 'comment' , 'label' => 'Комментарий', 'rules' => 'trim|required|htmlspecialchars'),
        array('field' => 'mark', 'label' => 'оценка', 'rules' => 'trim|required|htmlspecialchars'),
		array('field' => 'id_user', 'label' => '', 'rules' => 'trim|htmlspecialchars|max_length[64]'),
		array('field' => 'id_user_review' , 'label' => '', 'rules' => 'trim|htmlspecialchars|max_length[64]')
	),
    'createOffer' => array(
        array('field' => 'id_user', 'label' => 'Имя', 'rules' => 'trim|required|htmlspecialchars'),
        array('field' => 'id_project', 'label' => 'first name', 'rules' => 'trim|required|htmlspecialchars|callback_checkOffer'),
		array('field' => 'account_from', 'label' => 'Бюджет от', 'rules' => 'trim|numeric|htmlspecialchars|max_length[64]'),
		array('field' => 'account_to', 'label' => 'Бюджет до', 'rules' => 'trim|numeric|htmlspecialchars|max_length[64]'),
        array('field' => 'id_time_type', 'label' => 'Единицы времени', 'rules' => 'trim|htmlspecialchars|max_length[64]'),
        array('field' => 'time_from', 'label' => 'Время от', 'rules' => 'trim|numeric|htmlspecialchars|max_length[64]'),
        array('field' => 'time_to', 'label' => 'Время до', 'rules' => 'trim|numeric|htmlspecialchars|max_length[64]'),
        array('field' => 'currency', 'label' => 'Валюта', 'rules' => 'trim|htmlspecialchars|max_length[64]'),
        array('field' => 'comment', 'label' => 'Комментарий', 'rules' => 'trim|required|htmlspecialchars|max_length[1024]|callback_checkPortfolioWorks'),
        //array('field' => 'PortfolioList[]', 'label' => 'Портфолио', 'rules' => 'trim|htmlspecialchars|callback_checkOfferPortfolioAmount')

	),
    'addWork' => array(
        //array('field' => 'id_user', 'label' => 'first name', 'rules' => 'trim|required|htmlspecialchars'),
        //array('field' => 'id_project', 'label' => 'first name', 'rules' => 'trim|required|htmlspecialchars|callback_checkOffer'),
		array('field' => 'title', 'label' => 'Название', 'rules' => 'trim|required|htmlspecialchars|max_length[256]'),
		array('field' => 'description', 'label' => 'Описание', 'rules' => 'trim|required|htmlspecialchars|max_length[2056]'),
        array('field' => 'price', 'label' => 'Цена', 'rules' => 'trim|required|htmlspecialchars|max_length[64]'),
        array('field' => 'duration', 'label' => 'Временной промежуток', 'rules' => 'trim|required|htmlspecialchars|max_length[64]'),
        array('field' => 'id_currency', 'label' => 'Валюта', 'rules' => 'trim|required|htmlspecialchars|max_length[64]'),
        array('field' => 'picture', 'label' => 'Аватар', 'rules' => 'trim|htmlspecialchars')
	)

);