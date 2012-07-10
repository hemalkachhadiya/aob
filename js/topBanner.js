$(document).keyup(function(e) {
    if(e.keyCode== 27) {
        $('.close').trigger('click');  
    }
});
function showPopup(Container){
        $('#popup-bg').show();
        $(Container).css({
           position:'absolute',
           /*left: ($(window).width()
             - $('#LocalPaymentPopup').outerWidth())/2, */
           top: ($(window).height()
             - $(Container).outerHeight())/2
        });
        $(Container).show();
        $(Container).focus();
    }
$(document).ready(function(){
    $('.InfoLink').live('click',function(e){
        e.preventDefault();
        $('.InfoPopupText').html($(this).attr('alt'));
        showPopup('.InfoPopup');

    })
    $('input[type=file]').change(function(e){
        $($(this).attr('alt')).html($(this).val());
    });

    //$('select').selectmenu("index", 4)
    $('#contact-us-link').live('click',function(e){
        e.preventDefault();
        showPopup('.ContactUsPopup');
        
    })
    $('.TechnicalSupportLink').live('click',function(e){
        e.preventDefault();
        $('#contact-us-link').trigger('click')
        $('.ContactUsMenuItem[value=1]').trigger('click')
    })
    $("#ContactUsForm").validate(
        {
            rules: {
                message: "required",
                type : "required"
            },
            messages:{
                message: "Поле Сообщение пустым быть не может",
                type : "Укажите Тип сообщения"
            },
            errorPlacement: function(error, element)
            {
                var errorHolder = $("#ContactUsForm .errorHolder");
                error.appendTo( errorHolder );
                //alert(error);
                console.log(error);
            },
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                        type : 'post',
                        clearForm: true,
                        url : '/main/setContactUs',
                        dataType : "json",
                        success: function(data) {
                            $("#ContactUsForm .errorHolder").html(data['message']);
                        }
                    });
            }
        });
    $('.ContactUsMenuItem').live('click',function(e){
       // e.preventDefault();
        var altTextValue = $(this).attr('value');
        //alert(altTextValue);
        $('.gray-popup-p').hide();
        $('.gray-popup-p[value="'+altTextValue +'"]').show();

    });


    $("span.timeago").timeago();
    $('.CarrouselNavigation a').live('click',function(e){
        e.preventDefault();
        var page = parseInt($(this).attr('page'));
        $('#top-navi li').removeClass('select');
        $(this).parent().addClass('select');
        var prevPage;
        var nextPage;

        if (page == 1){
            prevPage = 5;
        }else{
            prevPage = page - 1;
        }
        if (page == 5) {
            nextPage = 1;
        }else{
            nextPage = page + 1;
        }
        $('.prevCarrousel a').attr('page',prevPage);
        $('.nextCarrousel a').attr('page',nextPage);
        loadCarrouselUsers(page);
    })
    $('.prevCarrousel a,.nextCarrousel a').live('click',function(e){
        e.preventDefault();
        var page = $(this).attr('page');
        $('.CarrouselNavigation a[page="'+page+'"]').trigger('click');
    })
    // default loading carrousel users on every page
    $('.CarrouselNavigation a[page="1"]').trigger('click');
    /**
     * open/close top banner
     */
    $('#tb-l').click(function(){
        var marginTop;
        var linkText;
        var controlButton = $(this);
        if('opened' == controlButton.attr('switch')){
            marginTop = 0;
            linkText = 'Скрыть рекламный баннер';
            $(this).attr('switch','closed')
        }else{
            marginTop = $('#tb-a').innerHeight()-8;
            linkText = 'Показать баннер с рекламой';
            controlButton.attr('switch','opened')
        };
        $.ajax({
            url: '/bannermanager/setVisbility',
            type : 'POST',
            data :{
                    status : controlButton.attr('switch')
                  },
            success  : function(data){
                      },
            dataType : "json"
        });
        $('#tb-a').animate({'margin-top':'-'+marginTop+'px'});
        $('#tb-l').html(linkText);
	});

    function loadCarrouselUsers(page)
    {
        $.ajax({
            url: '/main/getCarrousel',
            type : 'POST',
            data :{
                    page : page
                  },
            success  : function(data){
                        var container = $('#CarrouselList');
                        container.html('');
                        console.log(data['list']);
                        $(data['list']).each(function(){
                            var departmnets = '';
                            var img = '/img/templates/default_user.png';
                            if (this.picture){
                                img = '/img/users/'+this.picture;
                            }
                            if (this.carrousel_comment){
                                departmnets = this.carrousel_comment;
                            }else{
                                $(this.departmentList).each(function(){
                                    departmnets += this.name+' ';
                                })
                            }

                            var link = this.id;
                            if (this.nickname){
                                link = this.nickname;
                            }
                            var expertImg = '';
                            if (this.expert == 1){
                                expertImg = '<span class="exp">expert</span>';
                            }
                            var template = '<li>'
                                                +'<a href="/user/'+link+'" title=""><img src="'+img+'" widht="50" height="50" title="" alt="">'
                                                + expertImg
                                                +'</a>'
                                                +'<a href="/user/'+link+'" title="">'+this.firstName+' '+this.lastName+'</a>'
                                                +'<p>'+departmnets+'</p>'
                                            +'</li>';
                            container.append(template)
                        });

                      },
            dataType : "json"
        });
    }


});
$(document).ready(function(){

    $('select[size!="10"]').styleSelect({
            				styleClass: "form-list",
            				optionsTop: 25,
            				jScrollPane:1,
            				jScrollPaneOptions :
            				{
            					showArrows: true
            				}
            			});
})
