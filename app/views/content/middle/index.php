<script src="/js/NewsLoader.js" ></script>
<div class="content" id="news-main">
				<h1><a href="news">Новости</a><span></span></h1>

                <span id="NewsContainer">

                </span>


				<a class="button" href="" title="" id="MoreNews"  type="news" amount="2" page="1">Загрузить еще</a>
			</div>
<div class="content" id="helpful">
				<h2><a href="/useful">Полезная информация</a></h2>

                <?  $newsListAmount = count($TemplateData['RandomUsefulList']);
                    if ($newsListAmount > 3 ){
                        $newsListAmount = 3;
                    }
                ?>
                <?  if (!empty($newsListAmount)):  ?>
                    <? for($i = 0 ; $i < $newsListAmount ; $i ++ ) { ?>
                        <div class="info-block">
        					<a href="<?= generaetPageLink($TemplateData['RandomUsefulList'][$i]) ?>" title=""><?= $TemplateData['RandomUsefulList'][$i]->title ?></a>
        					<p><?= $TemplateData['RandomUsefulList'][$i]->shortBody ?></p>
        					<span>—</span>
        				</div>


                    <? }  ?>
                <? endif; ?>
				<div id="block-about">
					<p>Мы — стабильная юридическая фирма, осуществляющая юридическое сопровождение и обслуживание бизнеса организаций в различных отраслях хозяйственной и иной экономической деятельности. Наша организация на рынке с 2003 года и объединяет специалистов различного профиля.</p>
					<div></div>
					<div></div>
				</div>
			</div>
<div class="content" id="lawyer-links">
				<h4><span><span>ССЫЛКИ ДЛЯ ЮРИСТА</span></span></h4>


                <? if (!empty($menu['bottom'])) : ?>
                   <ul >

                       <?

                       for ($i = 0 ; $i < count($menu['bottom'] );$i++ ) {
                            $addClass = '';
                            if ($i % 5 == 0) {
                                $addClass = 'class="new-row"';
                            }
                           $item = $menu['bottom'][$i];
                       ?>

                           <li <?= $addClass ?> ><a href="" title=""><?= $item->title ?></a></li>
                       <? } ?>
                   </ul>
                <? endif; ?>


			</div>
