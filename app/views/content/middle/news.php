<div id="content-konsatling">
    <article id="article">
        <article class="article-block">
            <header class="header-h1">
                <h1>Новости</h1>
            </header>

        </article>
        <article class="article-block">
                        <header class="header-h2">
                            <h2><a href="/main/add/news?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">Добавить Новость</a>
                        </header>
        </article>
        <? if (!empty($TemplateData['news'])) : ?>
            <? foreach ($TemplateData['news'] as $item) : ?>
                <article class="article-block">
                    <header class="header-h2">
                        <h2><a href="/news_item?id=<?= $item->id ?>" ><?= $item->title ?></a> 

                        <? if ($this->authmanager->isAdmin()) { ?>
                            <a href="/main/delete/<?= $item->id ?>?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">
                                удалить
                            </a>
                        <? } ?>
                        </h2>
                    </header>
                    <section class="block-text-h2">
                        <p><?= smarty_modifier_mb_truncate(trim($item->body),250,'...',false,'UTF-8',false )?></p>
                    </section>
                </article>
            <? endforeach; ?>
        <? endif; ?>

    </article>

</div>