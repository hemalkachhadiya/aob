<div class="content" id="admin">
        <h1>
                <h1>Ссылки на главной</h1>
        </h1>
    <? $menuTitles = array(
        'top_left'      =>  'Верхнее левое',
        'top_middle'    =>  'Среднее',
        'top_right'     =>  'Верхнее правое',
        'bottom'        =>  'Нижнее'
        ); ?>
    <? foreach ($menu as $key => $ListItem) : ?>
        <h2>
            <?= $menuTitles[$key] ?>
        </h2>
        <? if (!empty($ListItem)) : ?>
            <? foreach ($ListItem as $item) : ?>

                <p>
                    <form action="/main/editMenu" method="post">
                        <?= $item->title?> &nbsp;
                        <input type="hidden" name="id" value="<?= $item->id?>">
                        <? if  ($item->link_default) : ?>
                            <?= $item->link_default ?>
                        <? else : ?>
                            <input type="text" name="link" value="<?= $item->link?>">
                            <input type="submit" value="редактировать">
                        <? endif; ?>
                    </form>


                </p>
            <? endforeach; ?>
        <? endif; ?>

    <? endforeach; ?>


</div>