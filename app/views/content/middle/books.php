<div id="content-konsatling">
				<article id="article">
					<article class="article-block">
						<header class="header-h1">
							<h1>Рекомендуем почитать </h1>
						</header>						
						<section class="block-text-h1">
							<p>В этом разделе мы публикуем ссылки на тематические издания, которые на наш профессиональный взгляд, настоятельно рекомендуются к прочтению.</p>
						</section>

					</article>
                    <? if ($this->authmanager->isAdmin()) : ?>
                        <a href="/main/add/books?redirect=<?= $_SERVER['REQUEST_URI'] ?>">Добавить Книгу</a>
                    <? endif; ?>
					<div id="slider1">
						<a class="buttons prev disableP" href="#">left</a>
						<div class="viewport">
                            <? if (!empty($TemplateData['books'])) : ?>
							<ul class="overview" style="width: 2160px; left: 0px; ">

                            <? foreach ($TemplateData['books'] as $item) : ?>
								<li>
									<img src="/images_content/books/small/<?= $item->picture ?>" width="234"  alt="">
									<div class="desc-book">
										<span class="author-book"><?= $item->title ?></span>

                                        <p><a href="<?= $item->link ?>"><?= strip_tags($item->body,"<br>") ?></a>
                                <? if ($this->authmanager->isAdmin()) : ?>
                                        <br>
                                            <a href="/main/delete/<?= $item->id ?>?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red"> Удалить</a> \
                                            <a href="/main/editPageById/<?= $item->id ?>" > Редактировать</a>
                                                <? endif; ?>
                                        </p>
									</div>
								</li>
                                <? endforeach; ?>
                                <!--
								<li>
									<img src="images/book1.png" width="234" height="300" alt="">
									<div class="desc-book">
										<span class="author-book">Е. Ветлужских </span>
										<p><a href="#">Стратегическая карта, системный подход и КPI</a></p>
									</div>
								</li>
								<li>
									<img src="images/book1.png" width="234" height="300" alt="">
									<div class="desc-book">
										<span class="author-book">Е. Ветлужских </span>
										<p><a href="#">Стратегическая карта, системный подход и КPI</a></p>
									</div>
								</li>									
								<li>
									<img src="images/book1.png" width="234" height="300" alt="">
									<div class="desc-book">
										<span class="author-book">Е. Ветлужских </span>
										<p><a href="#">Стратегическая карта, системный подход и КPI</a></p>
									</div>
								</li>
								<li>
									<img src="images/book1.png" width="234" height="300" alt="">
									<div class="desc-book">
										<span class="author-book">Е. Ветлужских </span>
										<p><a href="#">Стратегическая карта, системный подход и КPI</a></p>
									</div>
								</li>
								<li>
									<img src="images/book1.png" width="234" height="300" alt="">
									<div class="desc-book">
										<span class="author-book">Е. Ветлужских </span>
										<p><a href="#">Стратегическая карта, системный подход и КPI</a></p>
									</div>
								</li>-->
							</ul>
                            <? endif; ?>
						</div>
						<a class="buttons next" href="#">right</a>
						<span class="show-book">

                            Показаны <span>2</span> книги из <span><?= count ($TemplateData['books']) ?></span>
                        </span>

					</div>
					<article class="article-block">
						<header class="header-h2">
							<h2>Бизнес-консультирование</h2>	
						</header>
                        <? if (!empty($TemplateData['quotes'])) : ?>
                        <? foreach ($TemplateData['quotes'] as $item) : ?>
						<section class="block-text-h2 quote">
							<p><span class="red-sk left-span">«</span><?= strip_tags($item->body,"<br>") ?><span class="red-sk rights-span">»</span></p>
							<section class="right-block">
									<div class="right-block-in">
										<span class="bold-text"><?= $item->title ?>
                            <? if ($this->authmanager->isAdmin()) : ?>
                                <br>

                                        <a href="/main/delete/<?= $item->id ?>?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red"> Удалить</a> \
                                        <a href="/main/editPageById/<?= $item->id ?>" > Редактировать</a>
                                            <? endif;?>
                                        </span>
									</div>
							</section>
						</section>
                        <? endforeach; ?>
                        <? endif; ?>
					</article>

                    <? if ($this->authmanager->isAdmin()) : ?>
                    <article class="article-block">
                    						<header class="header-h2">
                    							<h2><a href="/main/add/quote?redirect=<?= $_SERVER['REQUEST_URI'] ?>" > Добавить Цитату</a></h2>
                    						</header>

                    </article>
                    <? endif; ?>

				</article>				
		    </div>