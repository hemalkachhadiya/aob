<? if ($this->authmanager->isLogged()) {
    if (!$userData->expert) { ?>
    <p id="free-response">
        <? if ($userData->type == 1) { ?>
            У вас осталось <span><?= $userData->FreeOffers ?> <?= declension($userData->FreeOffers,array('бесплатный','бесплатных')); ?> <?= declension($userData->FreeOffers,array('ответ','ответа','ответов')); ?></span> на проект.
            <a href="" title="" class="BuyLink" type="system" alt="expert">Купите аккаунт</a> <span class="babl-expert">expert</span> и работайте без ограничений.
        <? } else if ($userData->type == 2){ ?>
            У вас осталось <span><?= $userData->FreeProjects ?> <?= declension($userData->FreeProjects,array('публикация','публикации','публикаций')); ?> <?= declension($userData->FreeProjects,array('проект','проекта','проектов')); ?></span> на этой неделе.
            <a href="" title="" class="BuyLink" type="system" alt="expert">Купите аккаунт</a> <span class="babl-expert-r">expert</span> и работайте без ограничений.
        <? } ?>

    </p>
    <? } ?>
<? } ?>
