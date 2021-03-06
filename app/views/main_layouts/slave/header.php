<? if ($this->authmanager->isAdmin() ) : ?>
<ul class="AdminBlock">
	<li><a href="/login">Пароль</a></li>
	<li><a href="/pages">Статические страницы</a></li>
	<li><a href="/menu">Меню</a></li>
	<li><a class="LogoutLink" href="/main/logout">Выйти</a></li>
</ul>
<? endif; ?>

<div id="header">
    <a id="logo" href="/" title=""><img src="/img/logo-0.jpg" alt="" title=""></a>
    <div id="our-services">
        <? if (!empty($menu['top_left'])) : ?>
            <ul >
                <? foreach ($menu['top_left'] as $item) : ?>
                    <?  $class = '';
                        if ("/page/".$ActiveTemplate == $item->link) :
                            $class = 'class="select"';
                        endif;
                    ?>

                    <li <?= $class  ?> ><a href="<?= setMenuLink($item) ?>" title=""><?= $item->title ?></a></li>
                <? endforeach ?>
            </ul>
        <? endif; ?>
        <? if (!empty($menu['top_middle'])) : ?>
            <ul >
                <? foreach ($menu['top_middle'] as $item) : ?>
                    <?  $class = '';
                        if ("/page/".$ActiveTemplate == $item->link) :
                            $class = 'class="select"';
                        endif;
                    ?>
                    <li <?= $class  ?>><a href="<?= setMenuLink($item) ?>" title=""><?= $item->title ?></a></li>
                <? endforeach ?>
            </ul>
        <? endif; ?>

        <div></div>
        <div></div>
    </div>


    <? if (!empty($menu['top_right'])) : ?>
    <ul id="main-menu">
        <? foreach ($menu['top_right'] as $item) : ?>
            <?  $class = '';
                if ($ActiveTemplate == $item->link_default) :
                    $class = 'class="select"';
                endif;
            ?>

            <li <?= $class ?>><a href="<?= setMenuLink($item) ?>" title=""><?= $item->title ?></a></li>

        <? endforeach ?>
    </ul>
    <? endif; ?>
    <div class="border"></div>
</div>