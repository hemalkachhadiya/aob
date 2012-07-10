<!--
<header id="header">
    <div id="logo">
        <a href="/"><img src="/images/logo.gif" width="243" height="41" alt="KPI Solutions"> </a>
    </div>
    <nav id="navigation">
        <ul>
            <?
                $data = array(
                    'consulting'                =>  'Консталинг',
                    'workshops_and_trainings'   =>  'Семинары и тренинги',
                    'coaching'                  =>  'Коучинг',
                    'contacts'                  =>  'Контакты'
                );
                foreach ($data as $key => $value):
                    $current = '';
                    $item    = '';
                    $angles  = '';
                    if ($ActiveTemplate == $key){
                        $current    = 'current';
                        $angles     = '<b class="current-l"></b><b class="current-r"></b>';
                    }

                    if ($ActiveTemplate != 'conslting')
                        $item = 'item';

            ?>
                    <li class="<?= $current ?> <?= $item ?>" ><a href="/page/<?= $key ?>"><?= $value ?></a>
                        <?= $angles ?>
                    </li>
            <?
                endforeach;
            ?>
        </ul>
    </nav>
</header> -->
<div id="header">
    <a id="logo" href="/" title=""><img src="img/logo-0.jpg" alt="" title=""></a>
    <div id="our-services">
        <ul>
            <li><a href="" title="">Налоговые споры</a></li>
            <li><a href="" title="">Взыскание задолженостей</a></li>
            <li><a href="" title="">Сделки с недвижимостью</a></li>
            <li><a href="" title="">Корпоративное право</a></li>
            <li><a href="" title="">Готовые ЗАО</a></li>
        </ul>
        <ul>
            <li><a href="" title="">Перерегистрация ООО</a></li>
            <li><a href="" title="">Страховые споры</a></li>
            <li><a href="" title="">Представительство в арбитражном суде</a></li>
            <li><a href="" title="">Строительно-инвестиционная деятельность</a></li>
            <li><a href="" title="">Иные юридические услуги</a></li>
        </ul>
        <div></div>
        <div></div>
    </div>

    <? if (!empty($menu['top_right'])) : ?>
    <ul id="main-menu">
        <? foreach ($menu['top_right'] as $item) : ?>
            <li><a href="" title=""><?= $item->title ?></a></li>
        <? endforeach ?>
    </ul>
    <? endif; ?>
    <div class="border"></div>
</div>