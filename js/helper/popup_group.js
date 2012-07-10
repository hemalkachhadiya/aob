
$(".GroupActionConfirm").live('click',function(e){
    e.preventDefault();

    var cause = $('input[name="cause"]:checked').attr('cause_message')
    if (cause === undefined){cause=''}
    //alert(cause);
    //cause_message
    var action = $(this).attr("action");
    var groupId = $(this).attr("groupid");
    var button = $('.ShowPopup[groupid="'+groupId+'"]');

    $.ajax({
      url: '/groups/'+action+'Group',
      type : 'POST',

      data :{
                'message'     : $('textarea[name="message"]').text(),
                'groupid'     : groupId,
                'cause'       : cause
            },
      success  : function(data){
                    switch (action) {
                        case "join"  :
                            $("#GroupJoinActionPopup span.submit-status").show();
                            setTimeout(function(){
                                $("#GroupJoinActionPopup span.submit-status").fadeOut();
                                $("#GroupJoinActionPopup").hide();
                            }, 2000);
                            //$.popup.alert({title: 'Message Successfully Sent'});
                            button.removeClass("orange");
                            button.removeClass("actions");
                            button.addClass("grey");
                            button.text("Request Sent");

                        break;
                        case "leave" :
                            button.removeClass("red");
                            button.addClass("orange");
                            button.text("Join");
                            button.attr("action","join");
                        break;
                    }
                    closePopups();
                },
      dataType : "json"
  });

})
function closePopups(){
    $(".popup").hide();
}
$('.ShowPopup').live('click',function(e){
    e.preventDefault();
    closePopups();
    var button = $(this);
    $(".GroupName").html(button.attr("groupname"));
    $(".GroupActionConfirm").attr("groupid",button.attr("groupid"))
    var popupId = $(this).attr('popup');
    $('#'+popupId).show();
})
$('.knob').live('click',function(e){
    e.preventDefault();
    closePopups();
})
