$(document).ready(function(){


    $.ajax({
                    url: '/main/getAjaxPartnerStatistics',
                    type : 'POST',

                    success  : function(data){

                                var arrayOfDataTMP = new Array(
                         				 [0,'янв'],
                         				 [1000,'фев'],
                         				 [7000,'март'],
                         				 [2000,'апр'],
                         				 [4000,'май'],
                         				 [6000,'июнь'],
                         				 ['','июль'],
                         				 ['','авг'],
                         				 ['','сен'],
                         				 ['','окт'],
                         				 ['','ноб'],
                         				 ['','дек']
                         			);
                                var arrayOfData =  data;
                                var minConfigAmount = 12;
                                if ( arrayOfData.length < minConfigAmount  ){
                                    for (var i = arrayOfData.length; i < minConfigAmount; i++) {
                                         arrayOfData[i] = ['',''];
                                    }
                                }


                                console.log(arrayOfData);
                                console.log(arrayOfDataTMP);

                                $('#grafic-content').jqBarGraph({
                                        data: arrayOfData,
                                        width: 960,
                                        height: 385,
                                        barSpace: 0,
                                        color: "transparent"

                                    });
                              },
                    dataType : "json"
                })



    function loadQuestions(page){
        $.ajax({
                url: '/main/getQuestionList',
                type : 'POST',
                data :{
                        page : page
                      },
                success  : function(data){
                            var container = $('#QuestionListContainer');
                            console.log(data['list']);
                            $(data['list']).each(function(){
                                var block = getTemplate(this);
                                container.append(block);
                                console.log(block);
                            });
                            if (data['isMore']){
                                $('#more-question').attr('page',parseInt($('#more-question').attr('page'))+1)
                            }else{
                                $('#more-question').hide();
                            }
                            console.log(container);
                          },
                dataType : "json"
            });
    }
    $('p[type="question"]').live('click',function(e){
        e.preventDefault();
        $(this).parent('.p-q-answer').find('p[type="answer"]').toggle();
    })


    $('#more-question').live('click',function(e){
        e.preventDefault();
        var page = $(this).attr('page');
        loadQuestions(page);
    })
    $('#more-question').trigger('click');
    function displayPhoto(photo){
        console.log(photo);
        if (photo)
        {
            return '/img/users/'+photo;
        }else{
            return 'img/templates/no-photo.jpg';
        }
    }
    function setLink(user){
        if (user.nickname){
            return '/user/'+user.nickname;
        }else{
            return '/user/'+user.id_user;
        }
    }
    function setName(user){
        if (user.nickname){
            return user.nickname;
        }else{
            return user.firstName+' '+user.lastName ;
        }
    }
    function getTemplate(entity){
        var className = '';
        var answer = '';
        if (entity.answer){
            className = 'p-q-done';
            answer = '<p type="answer" style="display:none"><span>'+'ОТВЕЧАЕТ '+entity.answerWho+' ИЗ ГИЛЬДИИ FREE-WRITE.RU'+'</span><br/>'+entity.answer+'</p>';
        }
        var template = '<div class="'+className+'">'
                            +'<div class="p-q-author">'
                                +'<a href="'+setLink(entity)+'" title="">'+setName(entity)+'</a>'
                                +'<a class="p-q-ava" href="'+setLink(entity)+'" title=""><img src="'+displayPhoto(entity.picture)+'" title="" alt=""></a>'
                                +'<div class="p-q-l"></div>'
                                +'<div class="p-q-c"></div>'
                            +'</div>'
                            +'<div class="p-q-answer">'
                                +'<p type="question"><span>— '+entity.question+'</span></p>'
                                +answer
                                +'<div class="p-q-t"></div>'
                                +'<div class="p-q-c"></div>'
                                +'<div class="p-q-b"></div>'
                            +'</div>'
                        +'</div>';
        return template;
    }
    $('#AskQuestionLink').live('click',function(e){
        e.preventDefault();
        showPopup(".AskQuestionPopup");
    })

    $('#AskQuestionForm').validate(
        {
            rules: {
                question: "required"

            },
            messages:{
                question: "Поле Вопроса пустым быть не может"

            },
            errorPlacement: function(error, element)
            {
                var errorHolder = $("#AskQuestionForm .errorHolder");
                error.appendTo( errorHolder );
                //alert(error);
                console.log(error);
            },
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                        type : 'post',
                        clearForm: true,
                        url : '/main/sendQuestion',
                        dataType : "json",
                        success: function(data) {
                            $("#AskQuestionForm .errorHolder").html(data['message']);
                        }
                    });
            }
        });

    $('.promoAction,#ReturnToBasicView').live('click',function(e){
        e.preventDefault();
        $("#promo-ref-code").toggle();
        $("#basic-promo-block").toggle();
    })
/*
    $("#promo-w-link").live('click',function(e){
        e.preventDefault();
        $('.promo-white').toggle();
    })*/
    $("#promo-w-link").mouseenter(function(){
        $('.promo-white').toggle();
    }).mouseleave(function(){
        $('.promo-white').toggle();
    });



})
