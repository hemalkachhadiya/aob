    $(document).ready(function(e){

        $('#PasswordRecoveryLink').live('click',function(e){
            e.preventDefault();
            $('.close').trigger('click');
            showPopup('.PasswordRecoveryDialog');
            $('#PasswordRecoveryForm').show();
        });
        $('#PasswordRememberLink').live('click',function(e){
            e.preventDefault();
            $('.close').trigger('click');
            $('.loginAction').trigger('click');
        });


        $('#StatisticsLink').live('click',function(e){
            e.preventDefault();
            showPopup('#StatisticsPopup');
        });

        $('.loginAction').live('click',function(e){
            e.preventDefault();
            /*$('#'+$(this).attr('popup')).toggle();
            $('#popup-bg').toggle(); */

            var popupContainer = '#'+$(this).attr('popup');
            var attr = $(this).attr('attr');
            showPopup(popupContainer);
            $('.AuthActionHeader[attr="'+attr+'"]').trigger('click');

            if ($(this).attr('type')){
                $('input[name="spec"][value="'+$(this).attr('type')+'"]').trigger('click');
            }

        })
        $('.close').live('click',function(e){
            e.preventDefault();
            $('.popup-win,.promo-popup').hide();
//            $('#popup-bg').toggle();
            $('#popup-bg').hide();
            $('#login').css('height','435px');
        })
        
        $('#AccountLink').live('click',function(e){
            e.preventDefault();
            showPopup('#'+$(this).attr('popup'));
        })

        $('.AuthActionHeader').live('click',function(e){
            e.preventDefault();
            if ($(this).attr('attr') == 'signup'){
                $('#login').css('height','750px')
            }else{
                $('#login').css('height','435px')
            }
            $("#login form").hide();
            $('.AuthActionHeader').removeClass('select');
            $(this).addClass('select');
            $('#'+$(this).attr('attr')+'Form').show();
        })
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

        $("#PasswordRecoveryForm").validate(
            {
                errorPlacement: function(error, element)
                {
                    var errorHolder = $("#PasswordRecoveryForm .errorHolder");
                    error.appendTo( errorHolder );
                    //alert(error);
                    console.log(error);
                },
                messages:{
                    email :{
                        'required'  : 'Поле "Почта" обязательно для заполнения',
                        'email'     : 'Поле "Почта" должно иметь формат email'
                    }
                },
                submitHandler: function(form) {
                    $(form).ajaxSubmit({
                            type : 'post',
                            clearForm: true,
                            url : '/main/recover',
                            dataType : "json",
                            success: function(data) {
                                $("#PasswordRecoveryForm .errorHolder").html(data['message']);

                            }
                        });
                }
            });
        $("#signupForm").validate(
        {
            rules: {
                password: "required",
                firstName : "required",
                lastName : 'required',
                code : 'required',
                spec : 'required'

                /*password_again: {
                  equalTo: "#password"
                }*/
            },
            messages:{
                password : 'Пароль обязателен для заполнения',
                firstName : 'Имя обязательно для заполнения',
                lastName : 'Фамилия обязательна для заполнения',
                code : 'Введите подтверждающий код',
                /*password_again: {
                                  equalTo: "Пароли не совпадают"
                                },*/
                login :{
                    'required'  : 'Введите ваш эл. адрес',
                    'email'     : 'Эл. адрес должен иметь соответствующий формат '
                }  ,
                spec :  'Категория не указана'
            },
            errorPlacement: function(error, element)
            {
                var errorHolder = $("#signupForm .errorHolder");
                error.appendTo( errorHolder );
                //alert(error);
                console.log(error);
            },
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                        type : 'post',
                        //clearForm: true,
                        url : '/main/createUser',
                        dataType : "json",
                        success: function(data) {
                            $("#signupForm .errorHolder").html(data['message']);
                            if (data['status'])
                            {
                                window.location.href = '/profile';
                            }
                        }
                    });
            }
        });

    })
