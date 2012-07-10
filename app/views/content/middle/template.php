<div id="content-konsatling">
    <? if ($this->authmanager->isAdmin()) : ?>
        <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
        <?= validation_errors('<div class="error">','</div>') ?>
        <div>
        <form method="post" action="<?= $_SERVER["REQUEST_URI"]; ?>" enctype="multipart/form-data">

                <input type="hidden" value="<?= $TemplateData->id ?>" name="id" >
                Заголовок :  <input name="title" value="<?= setFormValue('title',$TemplateData) ?>" style="width:100%">
                <textarea rows="" cols="" name="body" class="ckeditor">

                    <?
                    if ( setFormValue('body',$TemplateData))
                        echo setFormValue('body',$TemplateData);
                    else
                        $this->load->view('content/middle/default_template');
                    ?>
                </textarea>

                <? if(!empty($WideRights)) : ?>
                    <? if ($TemplateData->typeName == 'books') : ?>
                        Сcылка :
                        <input name="link" type="text" value="<?= setFormValue('link',$TemplateData) ?>" style="width:100%">
                    <? endif; ?>

                    <? if ($TemplateData->typeName == 'books'
                            || $TemplateData->typeName == 'review') : ?>
                        Картинка :
                        <input name="userfile"  type="file" style="width:100%">
                        <input name="photo_template"  type="hidden" value="<?= $TemplateData->typeName ?>" >
                        <?  if ($TemplateData->picture) { ?>
                            <img src="/images_content/<?= $TemplateData->typeName ?>/small/<?= $TemplateData->picture ?>" >
                        <? }?>
                            
                    <? endif; ?>

                    <? if ($TemplateData->typeName == 'review') : ?>
                        <br>
                        Дополнительная информация :
                        <input name="picture"  type="text" value="<?= setFormValue('additional_info',$TemplateData) ?>"  style="width:100%">
                    <? endif; ?>


                <? endif; ?>

                <p>
                    <input type="submit" value="сохранить">
                </p>

        </form>
        </div>
    <? else : ?>




    <article id="article">
        <article class="article-block">
            <header class="header-h1">
                <h1><?= $TemplateData->title ?></h1>
            </header>

        </article>
        <? if (!empty($TemplateData->body)) :  ?>
            <?= $TemplateData->body ?>
        <? else: ?>
            <?= $this->load->view('content/middle/default_template') ?>
        <? endif; ?>

    </article>

    <? endif; ?>
</div>