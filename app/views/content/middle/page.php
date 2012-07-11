<div class="content" id="article">
        <h1>
                <h1>Статические страницы</h1>
        </h1>
        <? if ($this->authmanager->isAdmin()) : ?>

                                <h2><a href="/main/add/page?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">Добавить Страницу</a>

        <? endif; ?>

        <? if (!empty($TemplateData['list'])) : ?>
            <? foreach ($TemplateData['list'] as $item) : ?>

                        <h2><a href="/page?id=<?= $item->id ?>" ><?= $item->title ?></a>

                        <? if ($this->authmanager->isAdmin()) { ?>
                            <a href="/main/delete/<?= $item->id ?>?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">
                                удалить
                            </a>
                        <? } ?>
                        </h2>


                        <p><?= smarty_modifier_mb_truncate(trim($item->body),250,'...',false,'UTF-8',false )?></p>

            <? endforeach; ?>
        <? endif; ?>



</div>