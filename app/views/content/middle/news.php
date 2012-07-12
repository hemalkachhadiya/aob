<script src="/js/NewsLoader.js" ></script>
    <div class="content" >
        <h1>Новости<span></span></h1>
        <? if ($this->authmanager->isAdmin()) : ?>
                    <a href="/main/add/news?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">Добавить Новость</a>
        <? endif; ?>

        <span id="NewsContainer">
        </span>
    <a class="button" href="" title="" amount="5" type="news" id="MoreNews" page="1">Загрузить еще</a>
</div>