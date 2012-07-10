<div id="content-konsatling">
    <article id="article-feed">
        <article class="article-block">
            <header class="header-h1">
                <h1>Отзывы</h1>
            </header>
            <section class="block-text-h1">
                <p>В этом разделе мы публикуем ссылки на тематические издания, которые на наш профессиональный взгляд, настоятельно рекомендуются к прочтению.</p>
            </section>
        </article>
    <?    if (!empty($TemplateData['reviews'])) : ?>

        <article class="article-block">

            <? foreach ($TemplateData['reviews'] as $item) : ?>
                <section class="block-text-h2 quote">
                    <p><span class="red-sk left-span">«</span><?= strip_tags($item->body,"<br>" )?><span class="red-sk rights-span">»</span></p>
                    <section class="right-block">
                        <div class="right-block-in">
                            <img src="/images_content/review/square/<?= $item->picture ?>" width="100" height="100" alt="">
                            <span class="bold-text">
                                <?= $item->title ?>,</span> <?= $item->additional_info ?>
                            <? if ($this->authmanager->isAdmin()) : ?>
                                <br>
                                    <a href="/main/delete/<?= $item->id ?>?redirect=<?= $_SERVER['REQUEST_URI'] ?>" style="color:red"> Удалить</a> \
                                    <a href="/main/editPageById/<?= $item->id ?>" > Редактировать</a>
                            <? endif; ?>
                        </div>
                    </section>
                </section>
            <? endforeach; ?>
            <!-- <section class="block-text-h2 quote">
                <p><span class="red-sk left-span">«</span>Спасибо большое! Было интересно, расширило сознание, ознакомило с новыми представлениями о возможностях, существующих моделях, системах.</p>
                <p>Много информации, хочу поварить еще, переварить, что-то буду пробовать применять в дальнейшем. Интересно было послушать всех докладчиков. Узнать, как что происходит в других компаниях. Про ***, конечно, что рассказали, молодцы! Такое, конечно, нигде не говорят, хоть и делают :)))</p>
                <p>Хорошее помещение, порадовало в Digital October. Вопросы и мысли по улучшению, возможно, я обрету в своей голове, возможно, через какое-то время, прямо сейчас сложно составить.<span class="red-sk rights-span">»</span></p>
                <section class="right-block">
                    <div class="right-block-in">
                        <img src="images/foto2.jpg" width="100" height="100" alt="">
                        <span class="bold-text">Ольга Шашкова,</span> Санкт-Петербург
                    </div>
                </section>
            </section> -->
        </article>
    <? endif; ?>
    </article>
</div>
<?  $display = '';
    if (!$this->authmanager->isLogged()):
        $display = 'style="display:none"';
    endif;
?>
<div class="content" <?= $display ?>>
    <div class="feedback-block">
        <header class="header-h2">
            <h2>Ваш отзыв</h2> <a href="/main/add/review?redirect=<?= $_SERVER['REQUEST_URI']?>" class="">добавить</a>
        </header>
<!--
        <div class="feedback-table">
            <div class="errorHolder"></div>
            <form method="get" name="feed" action="" id="ReviewForm">

                <table>
                    <tbody><tr>
                        <td class="first-td">Имя и фамилия</td>
                        <td class="second-td">
                            <input type="text" name="user_name" class="input-text required" value="" placeholder="Константин Константинопольский">
                        </td>
                    </tr>
                    <tr>
                        <td class="first-td">Ваш город</td>
                        <td class="second-td">
                            <input type="text" name="city" class="input-text required" value="" placeholder="Москва">
                        </td>
                    </tr>
                    <tr>
                        <td class="first-td">Портрет</td>
                        <td class="second-td">
                            <input type="file" class="feed-file-btn2 required" name="userfile">
                            <span class="feed-file-btn"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="first-td">Текст отзыва</td>
                        <td class="second-td">
                            <textarea rows="10" cols="45"
                                      class="required"
                                      name="content"
                                      placeholder="Решил написать здесь свой отзыв, и думаю с чего бы начать, т.к. мыслей накопилось достаточно много..."></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="first-td"> </td>
                        <td class="table-btn second-td">
                            <input type="submit" class="feed-btn" name="" value="Отправить">
                        </td>
                    </tr>
                </tbody></table>
            </form>
            <section class="right-block">
                <div class="right-block-in">
                    <p>Расскажите о посещенном семинаре или тренинге от нашей компании: что вам понравилось и что не понравилось, а также что вы бы хотели улучшить или просто поблагодарите наших спикеров.</p>
                    <p>Перед публикацией, все отзывы проходят человеческую подерацию.</p>
                </div>
            </section>
        </div>
        -->
    </div>
</div>