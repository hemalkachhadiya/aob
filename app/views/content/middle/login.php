<script>
    $(document).ready(function(e){
        $("#loginForm").validate(
                    {
                        errorPlacement: function(error, element)
                        {
                            var errorHolder = $("#loginForm .errorHolder");
                            error.appendTo( errorHolder );
                            //alert(error);
                            console.log(error);
                        },
                        messages:{
                            password : 'Пароль обязателен для заполнения',
                            login :{
                                'required'  : 'Логин обязателен для заполнения',
                                'email'     : 'Логин должен иметь формат email'
                            }  ,
                            spec :  'Категория не указана'
                        },
                        submitHandler: function(form) {
                            $(form).ajaxSubmit({
                                    type : 'post',
                                    clearForm: true,
                                    url : '/main/login',
                                    dataType : "json",
                                    success: function(data) {
                                        $("#loginForm .errorHolder").html(data['message']);
                                        if (data['status']){
                                           window.location.reload();
                                        }
                                    }
                                });
                        }
                    });
    })
</script>

<div class="content" id="news-main">
    <? if (!$this->authmanager->isLogged()) : ?>
        <h1>Вход<span></span></h1>
    <? endif; ?>
    <div class="news">
        <? if (!$this->authmanager->isLogged()) : ?>

                <form action="/main/login" method="POST" id="loginForm">
                    <div class="errorHolder"></div>
                    <div>
                        <!-- комментарий: for должен быть равен id элемента к которому относиться -->
                        <label for="inp-login">Эл. почта</label>
                        <input id="inp-login" type="text" name="login" class="email required" value="">
                    </div>
                    <div>
                        <!-- комментарий: for должен быть равен id элемента к которому относиться -->
                        <label for="inp-password">Пароль</label>
                        <input id="inp-password" type="password" name="password" class="required" value="">
                        <!---<a href="" title="" id="PasswordRecoveryLink"><span>Забыли пароль?</span></a> 00> -->
                    </div>
                    <input type='checkbox' name="expirationTime"> Запомнить меня
                    <input type="submit" name="" value="Войти">
                </form>
            <? else : ?>

                    <p>
                        <?
                            if ($this->session->userdata('ChangePassword')) :
                                echo $this->session->userdata('ChangePassword');
                                $this->session->unset_userdata('ChangePassword');
                            endif;
                        ?>
                        <form id="ChangePasswordForm" method="post" action="/main/changePassword">

                            <div>
                                <!-- комментарий: for должен быть равен id элемента к которому относиться -->
                                <label for="inp-password">Пароль</label>
                                <input id="inp-password" type="text" name="password" class="required" value="">
                                <input type="submit" value="Поменять пароль">
                            </div>

                        </form>

                    </p>

                <? endif; ?>
    </div>

</div>
