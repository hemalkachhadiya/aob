
<div>
    <a id="logo" href="<?= base_url()?>" title=""><img src="/img/logo-0.png" title="" alt="" /></a>
    <? if ($this->authmanager->isLogged()) { ?>
    <!-- START статистика пользователя если авторизирован -->
    <ul id="stat-panel">
        <li><a href="/profile/projects/<?= $userData->id ?>" title="" >Проекты


                <? if (!empty($userData->ProjectOffersAndAnswers)) : ?>
                (
                    <span id="ProjectOffersAndAnswers">
                        <?= $userData->ProjectOffersAndAnswers ?>
                    </span>
                )
                <? endif; ?>

        </a></li>
        <li id="my-basket"><a href="" title="Free-Checks - условная валюта нашего сайта, вы можете купить или вывести их по курсу 1 FC = 30 руб" title="" id="AccountLink" popup="recharge">Мой счёт</a><span>: <?= transformToFC($userData->balance) ?><!-- <span class="rubl">р<span>уб.</span></span></span>--></li>
    </ul>
    <!-- END статистика пользователя если авторизирован -->
    <? } ?>
    <ul id="user-panel">
        <? if ($this->authmanager->isLogged()) { ?>
            <!-- START пример пользовательской панели если авторизирован -->
            <? if ($this->authmanager->isAdmin()) { ?>
                <li id="dialog"><a href="/admin" target="_blank">Админ-панель</a></li>
                <li class="middot">&#183;</li>
            <?} ?>

            <li id="user-name"><a href="<?= setLink($userData)?>" title=""><?= $userData->frontPanelDisplayName ?></a></li>
            <li class="middot">&#183;</li>

            <? if ($userData->UnreadMailAmount) { ?>
                <li id="new-mess"><a href="/mail" title=""><?= $userData->UnreadMailAmount ?> <?= declension($userData->UnreadMailAmount , array('новое','новых'));?> <?= declension($userData->UnreadMailAmount , array('сообщение','сообщения','сообщений'));?></a></li>
            <? } else { ?>
                <li id="dialog"><a href="/mail" title="">Диалоги</a></li>
            <? } ?>
            <li id="logout"><a href="/main/logout" title="">Выйти</a></li>
        <? } else { ?>
        <!-- END пример пользовательской панели если авторизирован -->
        <!-- START пример пользовательской панели если не авторизирован -->
        <li><a href="" title="" class="loginAction" attr="login" popup="login">Вход</a></li>
        <li class="middot">&#183;</li>
        <li><a href="" title="" class="loginAction" attr="signup" popup="login">Регистрация</a></li>
        <!-- END пример пользовательской панели если не авторизирован -->
        <? } ?>
    </ul>
</div>