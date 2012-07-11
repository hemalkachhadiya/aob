<script>
    $(document).ready(function(){
        $('#ContactUsForm').validate(
                    {
                        errorPlacement: function(error, element)
                        {
                            var errorHolder = $("#ContactUsForm .errorHolder");
                            error.appendTo( errorHolder );
                            //alert(error);
                            console.log(error);
                        },
                        messages:{
                            email :{
                                'required'  : 'Почта обязательно для заполнения',
                                'email'     : 'Почта должна иметь формат email'
                            }  ,
                            content :  'Сообщение  обязательно для заполнения'
                        },
                        submitHandler: function(form) {


                            $(form).ajaxSubmit({
                                    type : 'post',
                                    clearForm: true,
                                    url : '/main/send_contact_us',
                                    dataType : "json",
                                    success: function(data) {
                                        $("#ContactUsForm .errorHolder").html(data['message']);
                                        /*
                                        if (data['status']){
                                           window.location.reload();
                                        }*/
                                    }
                                });
                        }
                    });
    })

</script>
<h4>ФОРМА ОБРАТНОЙ СВЯЗИ</h4>
<form id="ContactUsForm">
    <div class="errorHolder"></div>
    <input type="text" name="email" placeholder="Адрес электронной почты" class="required email" value="">
    <!-- <div class="errorHolder">Это какая то ошибка</div>
    <div class="errorHolder">Это какая то ошибка</div> -->
    <textarea placeholder="Сообщение нам" name="content" class="required"></textarea>
    <input class="button" type="submit" name="" value="Отправить">
</form>
