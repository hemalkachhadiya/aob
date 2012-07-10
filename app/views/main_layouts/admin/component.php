<!DOCTYPE html>
<html><head>
<title>HTML KickStart</title>
<meta charset="UTF-8">
<meta name="description" content="" />
<script src="/js/main/jquery-1.6.2.js"></script>

<script type="text/javascript" src="/js/kickstart/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="/js/kickstart/kickstart.js"></script>                                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="/css/kickstart/kickstart.css" media="all" />                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="/css/kickstart/style.css" media="all" />                          <!-- CUSTOM STYLES -->
</head><body><a id="top-of-page"></a><div id="wrap" class="clearfix">
<!-- ===================================== END HEADER ===================================== -->


	<!-- 
	
		ADD YOU HTML ELEMENTS HERE
		
		Example: 2 Columns
	 -->
	 <!-- Menu Horizontal -->
	<ul class="menu">
	<!-- <li class="current"><a href="">Item 1</a></li> -->
        <li <? if ($ActiveDirectory == 'Pages' ) { ?> class="current" <? } ?>><a href=""><span class="icon" data-icon="R"></span>Страницы</a>
            <ul>
            <? foreach ($StaticPages as $page) { ?>
                <li>
                    <a href="/admin/editStaticPage/<?= $page->id ?>">
                    <? if ($page->system) { ?>
                        <span class="icon x-small gray" data-icon="!"></span>
                    <? } ?>
                    <?= $page->title ?></a>
                </li>
            <? } ?>

            <!--
            <li class="divider"><a href=""><span class="icon" data-icon="T"></span>li.divider</a></li> -->
            </ul>
        </li>
        <li <? if ($ActiveDirectory == 'Departments' ) { ?> class="current" <? } ?>>
            <a href="/admin/departments">Дисциплины</a>
        </li>
        <li <? if ($ActiveDirectory == 'ContactUs' ) { ?> class="current" <? } ?>>
                   <a href="/admin/contactus">Обратная связь</a>
               </li>
        <li <? if ($ActiveDirectory == 'Questions' ) { ?> class="current" <? } ?>>
                   <a href="/admin/questions">Вопросы</a>
               </li>
	</ul>
	 
<div class="col_12">
	<? $this->load->view('content/'.$ContentTemplate)?>
</div>

<!-- ===================================== START FOOTER ===================================== -->
<div class="clear"></div>
<div id="footer">

<a id="link-top" href="#top-of-page">Наверх</a>
</div>

</div><!-- END WRAP -->
</body></html>