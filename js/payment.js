
$(document).ready(function(){
    $('.BuyLink').live('click',function(e){
        e.preventDefault();

        $("#BuyForm .errorHolder").html('');
        /*$('#LocalPaymentWhat').html($(this).attr('what'))
        $('#LocalPaymentPrice').html($(this).attr('price')) */
        $('#LocalPaymentTarget').val($(this).attr('alt'))

        var template = $(this).attr('alt');
        if ( parseInt(template) > 0 ){
            template = 'shop';
        }
        $('#LocalPaymentType').val($(this).attr('type'))
        $.ajax({
            url: '/payment/getPaymentTexts',
            type : 'POST',
            data :{
                    template : template
                  },
            success  : function(data){
                            $('.PopupServiceTitle').html(data.title);
                            $('.PopupServiceContent').html(data.content);

                      },
            dataType : "json"
        });

        $('.PaymentContent').load('/payment/getTemplate/'+template,{'price' : $(this).attr('price') },function() {
            $('select[size!="10"]').styleSelect({
                                				styleClass: "form-list",
                                				optionsTop: 25,
                                				jScrollPane:1,
                                				jScrollPaneOptions :
                                				{
                                					showArrows: true
                                				}
                                			});
        });


        $("#BuyForm .errorHolder").html("");
        showPopup('#LocalPaymentPopup');
    })
    $('#BuyForm').live('submit',function(e){
            e.preventDefault();
            $(this).ajaxSubmit({
            type : 'post',
            clearForm: true,
            url : '/payment/buy',
            dataType : "json",
            success: function(data) {
                //console.log(data);
                //console.log ("delim to debug error holding");

                $("#BuyForm .errorHolder").html(data['message']);
                $("#BuyForm .errorHolder").show();

                if (data['status'] == true){
                   window.location.reload();
                }
            }
        });
    })
    $('input[name="FirePaymentPopup"]').live('click',function(e){
        e.preventDefault();
        $('.close').trigger('click');
        showPopup('#PaymentConfirmationPopup');
    });
    $('#PaymentConfirmationPopup input[name="confirm"]').live('click',function(e){
        e.preventDefault();
        $('.close').trigger('click');
        showPopup('#LocalPaymentPopup');
        $('#BuyForm').trigger('submit');
    });
    
    $('#PaymentConfirmationPopup input[name="delete"]').live('click',function(e){
        e.preventDefault();
        $('.close').trigger('click');
    });



});
