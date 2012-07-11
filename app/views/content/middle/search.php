<? $list = $TemplateData['list'];
    $search = $TemplateData['search']; ?>
<h1>Поиск &mdash; <?= $search ?></h1>

    <? if (!empty($list)) : ?>
        <? foreach ($list as $item) : ?>
                <div class="info-block">
                    <a href="<?= generaetPageLink($item) ?>" title=""><?= $item->title ?></a>
                    <p><?= $item->shortBody ?></p>
                    <span>—</span>
                </div>
        <? endforeach;?>
    <? else: ?>
        <p>По вашому запросу ничего не найдено</p>
    <? endif; ?>