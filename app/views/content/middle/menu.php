<div class="content" id="admin">
        <h1>
                <h1>Ссылки на главной</h1>
        </h1>
    <? $menuTitles = array(
        'top_left'      =>  'Центральное меню [левая колонка]',
        'top_middle'    =>  'Центральное меню [правая колонка]',
        'top_right'     =>  'Правое меню',
        'bottom'        =>  'Ссылки для юриста'
        ); ?>

    <? foreach ($menu as $key => $ListItem) : ?>
        <h2>
            <?= $menuTitles[$key] ?>
        </h2>

        <a href="/main/addMenu/<?= $ListItem[0]->typeId ?>?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">
            добавить
        </a>
        <? if (!empty($ListItem)) : ?>
            <? foreach ($ListItem as $item) : ?>

                <p>
                    <table>
                <tr>
                    <td>
                        <form action="/main/editMenu" method="post">
                            <!-- <?= $item->title?> &nbsp; -->
                            <input type="hidden" name="id" value="<?= $item->id?>">
                            <input type="text" name="title" value="<?= $item->title ?>">
                            <? if  ($item->link_default) : ?>
                                <?= $item->link_default ?>
                            <? else : ?>
                                <input type="text" name="link" value="<?= $item->link?>">
                            <? endif; ?>
                            <input type="submit" value="редактировать">
                        </form>
                    </td>
                    <td>

                        <a href="/main/deleteMenu/<?= $item->id ?>?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red">
                            удалить
                        </a>
                    </td>
                </tr>
                    </table>




                </p>
            <? endforeach; ?>
        <? endif; ?>

    <? endforeach; ?>


</div>