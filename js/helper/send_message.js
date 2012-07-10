
        $('#SendMessageForm').live('submit',(function() {

            $(this).ajaxSubmit({
                type : 'post',
                clearForm: true,
                url : '/company/sendMessage',
                dataType : "json",
                success: function(data) {
                    //alert("message sent")
                    console.log(data);
                    closePopups();
                    $.popup.alert({title: 'message sent'});
                }
            });
            return false;
        }));