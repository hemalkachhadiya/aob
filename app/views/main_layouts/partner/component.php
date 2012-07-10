<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Free-Write.ru</title>
	<link type="text/css" rel="stylesheet" href="/css/main.css" />
    <!--<link type="text/css" rel="stylesheet" href="/css/smoothness/jquery-ui-1.8.20.custom.css" /> -->

	<link rel="shortcut icon" href="/img/favicon.ico" />
	<script src="/js/main/jquery-1.6.2.js"></script>
    <script src="/js/main/jquery-ui-1.8.15.js"></script>
        <script src="/js/main/jquery.validate.js"></script>
        <script src="/js/main/jquery.form.js"></script>

        <script src="/js/department_navigation.js"></script>
        <script src="/js/main/jquery.ui.selectmenu.js"></script>
        <script src="/js/topBanner.js"></script>
        <script src="/js/payment.js"></script>

        <script src="/js/jquery.mousewheel.js"></script>
        <script src="/js/jquery.jscrollpane.min.js"></script>
        <script src="/js/jquery.styleSelect.js"></script>
    <script src="/js/main/jqBarGraph.1.1.min.js"></script>

        <script src="/js/timeago.js"></script>
        <script src="/js/timeago.locale.js"></script>
        <script src="/js/login.js"></script>
        <script src="/js/partner.js"></script>
		
		<script src="/js/topBanner.js"></script>


</head>
<div id="popup-bg" style="display:none" ></div>
<body id="promo-bg">
	<div id="promo-body">
		<div>
			<div id="promo-header">
				<a id="promo-logo" href="<?= base_url() ?>" title="">
					<img src="/img/logo-0.png" title="" alt="" />
					<span>представляет</span>
				</a>
				<img id="promo-label" src="/img/img-test-0.png" title="" alt="" />
                <? if ($this->authmanager->isLogged()) { ?>
                    <div id="promo-user">
                        <a href="<?= setLink ($userData) ?> title=""><img src="<?= displayPhoto($userData->picture) ?>" width="40" height="40" title="" alt="" /></a>
                        <a href="<?= setLink ($userData) ?>" title=""><?= $userData->frontPanelDisplayName ?></a>
                        <a id="promo-exit" href="/main/logout" title="">Выйти</a>
                    </div>
                <? } else{  ?>
                    <ul id="promo-naviblock">
                        <li><a href="" title="" class="loginAction" attr="login" popup="login">Вход</a></li>
                        <li class="middot">·</li>
                        <li><a href="" title="" class="loginAction" attr="signup" popup="login">Регистрация</a></li>
                    </ul>
                <? } ?>
			</div>
			<div id="promo-adt">
				<div>
					<div id="promo-leather">
                       	<div id="adt-border">
							<? $this->load->view('content/partner/discount_block'); ?>
							<div id="basic-promo-block">
								<img src="/img/promo-h-0.png" title="" alt="" />
								<p>Всё просто: приглашай на наш сайт людей по своей индивидуальной ссылке и стабильно получай за это деньги.</p>
								<p>Кто до <a href="" title="">1 сентября</a> пригласит больше всего людей получит iPad 2!</p>
								<? if (!$this->authmanager->isLogged()) { ?>
                                    <a  id="take-promo" href="" title="" class="loginAction " attr="login" popup="login">Получить индивидуальную ссылку</a>
                                <? } else {?>
                                    <a id="take-promo" href="" title="" class="promoAction">Получить индивидуальную ссылку</a>
                                <? } ?>
								<p>и начать зарабатывать реальные деньги</p>
								<a href="" title="" id="promo-w-link">Правила партнёрской программы</a>

                                <!-- <div class="promo-white" style="display:none">
                                    <div>
                                        <p>Благодаря мобильности, встроенным возможностям и удобству среды для разработчиков, они идеальны для появления революционных приложений, которые уже изменили и продолжают менять наши представления об интерфейсах. Твиттер, Форсквер, Эверноут, Дропбокс, спорт- и фуд-трэкеры, журналы и книги на айпаде — больше, чем приложения с хорошим дизайном. Каждое из них создаёт новые форматы и сценарии, которые влияют на наш образ жизни.</p>
                                    </div>
                                    <div class="p-w-c"></div>
                                    <div class="p-w-b"></div>
                                </div> -->


							</div>
						</div>
					</div>
					<div id="p-adt-shadow">
						<div id="p-a-t"></div>
						<div id="p-a-c"></div>
						<div id="p-a-b"></div>
					</div>
				</div>
			</div>
			<div id="promo-stat">
				<ul id="promo-menu">
					<li>
                        <? if ($this->authmanager->isLogged()) { ?>
                            <a href="/partner/statistics">
                        <? } else { ?>
                            <a href="" title="" class="loginAction" attr="login" popup="login">
                        <? } ?>

                            <img src="/img/promo-h-1<? if ($ActiveDirectory == 'UserStatistics') echo "-a" ;?>.png" title="" alt="" />
                        </a>
                    </li>

					<li><img src="/img/promo-h-dot.png" title="" alt="" /></li>
					<li>

                        <a href="/partner"><img src="/img/promo-h-2<? if ($ActiveDirectory == 'UserRating') echo "-a" ;?>.png" title="" alt="" /></a>
                    </li>
				</ul>
                <? $this->load->view('content/partner/'.$MainTemplate) ?>
			</div>
			<div id="promo-faq">
				<img src="/img/promo-h-3.png" title="" alt="" id="QuestionListContainerSwitcher" />
                <span  id="QuestionListContainer" >

                </span>

			</div>
            <? if ($this->authmanager->isLogged()) { ?>
			<div id="promo-ask">
				<a href="" title="" id='AskQuestionLink'>Задать свой вопрос</a>
			</div>
            <? } ?>
		</div>
		<a id="more-question" href="" title="" page="1"><span><span>показать<br /> больше вопросов</span></span></a>
		<div id="promo-footer">
			
			<div id="p-f-l"></div>
			<div id="p-f-c">
				<p>&copy; Free-write.ru, 2012</p>
				<!-- место для соц линков -->
				<div id="made-by"></div>
			</div>
			<div id="p-f-r"></div>
		</div>
	</div>
	<div id="ipad2-bg"></div>
    <div class="promo-white" style="display:none;">
        <div>
            <p>Free-write.ru дарит всем своим пользователям возможность заработать, привлекая друзей на сайт. Вы получаете 25% скидки, которую можете разделить по своему усмотрению на 2 части - одна часть будет суммой, которую вы получаете за все купленные приглашенными вами людьми услуги, а вторая будет суммой скидки, которую получат приглашенные вами пользователи. Например вы отдаете 15% скидки своим друзьям и получаете 10% от стоимости всех купленных ими услуг за все время.</p>
        </div>
        <div class="p-w-c"></div>
        <div class="p-w-b"></div>
    </div>
</body>

<? $this->load->view('main_layouts/slave/popups') ?>
</html>