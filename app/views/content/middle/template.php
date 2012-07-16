

<div class="content" id="article">
    <? if ($this->authmanager->isAdmin()) : ?>
        <script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
        <?= validation_errors('<div class="error">','</div>') ?>
        <div>
        <form id="edit-article" method="post" action="<?= $_SERVER["REQUEST_URI"]; ?>" enctype="multipart/form-data">

                    Линк : /page/<input type="text" value="<?= setFormValue('template',$TemplateData) ?>" name="template" style="width:100%">

                <input type="hidden" value="<?= $TemplateData->id ?>" name="id" >
                Заголовок :  <input type="text" name="title" value="<?= setFormValue('title',$TemplateData) ?>" style="width:100%">

                <textarea  cols="" name="body" class="ckeditor" >

                    <?
                    if ( setFormValue('body',$TemplateData))
                        echo setFormValue('body',$TemplateData);
                    else
                        $this->load->view('content/middle/default_template');
                    ?>
                </textarea>
                <? if ($TemplateData->typeName == 'useful') : ?>
                    <br>
                    на главной
                        <select name="published">
                            <option value="0">не публиковать</option>
                            <option value="1" <? if ($TemplateData->published) : ?>selected <? endif; ?>>публиковать</option>
                        </select>




                <? endif; ?>



                <p>
                    <input type="submit" value="сохранить"> <a href="/main/delete/<?= $TemplateData->id ?>?redirect=" style="color:red">удалить</a>
                </p>

        </form>
           
        </div>
    <? else : ?>





                <h1><?= $TemplateData->title ?></h1>



        <? if (!empty($TemplateData->body)) :  ?>
            <?= $TemplateData->body ?>
        <? else: ?>
            <?= $this->load->view('content/middle/default_template') ?>
        <? endif; ?>



    <? endif; ?>
</div>