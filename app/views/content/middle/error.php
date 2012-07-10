<html lang="en-US">
    <? $this->load->view ('main_layouts/slave/head_tag') ?>
	<body>
        <div id="body404">
                <h1>404</h1>
                <p>Неправильно набран адрес <br>или такой страницы не существует.</p>
                <a id="logo" href="<?= base_url() ?>" title=""><img src="/img/logo-1.png" alt="" title=""></a>
                <a href="<?= base_url() ?>" title="">Вернуться на главную</a>
         </div>
    </body>
</html>