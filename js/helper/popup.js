function closePopups(){
    $(".popup").hide();
}
$('.mr5').live('click',function(e){
    e.preventDefault();
    closePopups();
    var popupId = $(this).attr('popup');
    $('#'+popupId).show();
})
/*$('.ml5').live('click',function(e){
    e.preventDefault();
    closePopups();
}) */
$('.knob').live('click',function(e){
    e.preventDefault();
    closePopups();
})
/*
$('#SendMessageForm').live('submit',function() {

    $(this).ajaxSubmit({
        type : 'post',
        clearForm: true,
        url : '/company/sendMessage',
        dataType : "json",
        success: function(data) {
            //alert("message sent")
            //console.log(data);
            $('#SendMessageForm').resetForm();
            $("#SendMessageForm span.submit-status").show();
			setTimeout(function(){$("span.submit-status").fadeOut();}, 2000);

            //closePopups();
            //alert("action complete");
        }
    });
    return false;
});*/
//function validateShareMessage(formData, jqForm, options) {
    // fieldValue is a Form Plugin method that can be invoked to find the
    // current value of a field
    //
    // To validate, we can capture the values of both the username and password
    // fields and return true only if both evaluate to true
$(document).ready(function(e){
    $("#SendMessageForm").validate(
    {
       rules: {
         message:{
           required: true,
           maxlength: 512
         },
         subject: {
           required: true
         }
       },
        messages: {
            subject: {
                required: "Subject is required."
            },
            message: {
                required: "Message is required.",
                maxlength: "Message max length is 512."
            }
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                    type : 'post',
                    clearForm: true,
                    url : '/company/sendMessage',
                    dataType : "json",
                    success: function(data) {
                        //alert("message sent")
                        //console.log(data);
                        $('#SendMessageForm').resetForm();
                        $("#SendMessageForm span.submit-status").show();
            			setTimeout(function(){
                            $("span.submit-status").fadeOut();
                            $("#SendMessagePopup").hide();
                        }, 2000);

                        //closePopups();
                        //alert("action complete");
                    }
                });
        }
    });
    $("#ShareForm").validate({
           rules: {
             message:{
               required: true,
               maxlength: 512
             },
             email: {
               required: true,
               email: true
             }
           },
            messages: {
                email: {
                    required: "Email is required."

                },
                message: {
                    required: "Message is required.",
                    maxlength: "Message max length is 512."
                },
                email: {
                    required: "Email is required."
                }
            },
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                        type : 'post',
                        clearForm: true,
                        url : '/mailbox/sendShare',
                        dataType : "json",
                        //beforeSubmit: validateShareMessage,
                        success: function(data) {
                            $('#ShareForm').resetForm();
                            $("#ShareForm span.submit-status").show();
                            setTimeout(function(){
                                $("span.submit-status").fadeOut();
                                $("#SharePopup").hide();
                            }, 2000);

                        }
                    });
            }
        });
})

    //return $("#ShareForm").valid();

//}
/*
$('#ShareForm').live('submit',function() {

    $(this).ajaxSubmit({
        type : 'post',
        clearForm: true,
        url : '/mailbox/sendShare',
        dataType : "json",
        beforeSubmit: validateShareMessage,
        success: function(data) {
            closePopups();
            $.popup.alert({title: 'Message Successfully Sent'});
            
        }
    });
    return false;
});*/
$('#ConnectForm').live('submit',function() {

    $(this).ajaxSubmit({
        type : 'post',
        clearForm: true,
        url : '/company/connectCompany',
        dataType : "json",
        success: function(data) {
            console.log(data);
            closePopups();
            if (data['status'] == true){
                //$.popup.alert({title: 'Message Successfully Sent'});
                $("#ConnectButton").html("Request sent");
                $("#ConnectButton").removeClass("orange");
                $("#ConnectButton").addClass("grey");
                $("#ConnectButton").removeAttr('popup');
            }
            
            //alert(data['message']);
            //alert("connect sent")
        }
    });
    return false;
});




