<script src="/js/NewsLoader.js" ></script>
    <div class="content" >
        <h1>Полезное<span></span></h1>
        <? if ($this->authmanager->isAdmin()) : ?>
                    <a class="add-content" href="/main/add/useful?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">Добавить Полезное</a>
        <? endif; ?>

        <span id="NewsContainer">
        </span>
    <a class="button" href="" title="" amount="5" id="MoreNews" type="useful" page="1">Загрузить еще</a>
</div>