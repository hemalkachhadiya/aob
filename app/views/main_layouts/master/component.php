<!doctype html>
<html>
    <? $this->load->view ('main_layouts/slave/head_tag') ?>
    <body>

        <div id="body">
            <div>
                <? $this->load->view('main_layouts/slave/header') ?>
                <? $this->load->view('content/'.$ContentTemplate); ?>
                <? $this->load->view('main_layouts/slave/social_plugin') ?>
            </div>


        </div>

        <? $this->load->view('main_layouts/slave/footer') ?>
    </body>


</html>
