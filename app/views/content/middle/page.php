<div class="content">
        <h1>Статические страницы<span></span></h1>
        <? if ($this->authmanager->isAdmin()) : ?>

                                <a class="add-content" href="/main/add/page?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">Добавить Страницу</a>

        <? endif; ?>

        <? if (!empty($TemplateData['list'])) : ?>
            <? foreach ($TemplateData['list'] as $item) : ?>
				<div class="news">
                        <a href="/page?id=<?= $item->id ?>" ><?= $item->title ?></a>

                        <? if ($this->authmanager->isAdmin()) { ?>
                            <a class="DeleteLink" href="/main/delete/<?= $item->id ?>?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">
                                удалить
                            </a>
                        <? } ?>
                        


                        <p><?= smarty_modifier_mb_truncate(trim($item->body),250,'...',false,'UTF-8',false )?></p>
				</div>
            <? endforeach; ?>
        <? endif; ?>



</div>