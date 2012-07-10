$(document).ready(function(e){
$('.GroupMemberLink').live('click',function(e){
            e.preventDefault();
            $("#GroupPopup").show();
            var groupId = $(this).attr('groupid');
            var groupName =  $(this).attr('groupname');
            $("#GroupName").text(groupName+' members');
            $.ajax({
                url: "/groups/getMembersAjax",
                type : 'POST',
                data :{
                    'groupid' : groupId
                },
                success  : function(data){
                            console.log(data);
                            var block;
                            $("#GroupContainer").html("");
                            $(data).each(function(item){
                                var imgSrc = '';
                                if (this.PhotoLink != null){
                                    imgSrc = '/images/user/small/'+this.PhotoLink;
                                }else{
                                    imgSrc = '/images/templates/avatar.gif';
                                }
                                block = '<div class="data-item c popup-data-item">'
                                            +'<div class="image l">'
                                              +'<img src="'+imgSrc+'"  width="60" height="60" alt="" />'
                                            +'</div>'
                                            +'<div class="content r">'
                                              +'<h4><a href="/company/'+this.companyId+'">'+this.companyName+'</a></h4>'
                                              +'<div class="description">'
                                                +this.stateTitle+', '+this.city+','+this.street
                                              +'</div>'
                                            +'</div>'
                                          +'</div>';
                                $("#GroupContainer").append(block);

                            })
                          },
                dataType : "json"
            });
        })

$('.group-actions').change(function() {
    var i = this.selectedIndex;

    if (i == 0) return;

    var $option = $(this).find('option').eq(i),
        type = $option[0].value,
        groupId = $option.data('groupid'),
        groupName = $option.data('groupname'),
        popup = $option.data('popup'),
        link = $option.data('link'),
        receiverType = $option.data('receiver-type');

    this.value = 0;
    this.selectedIndex = 0;
    if (type == 'SendMessage') {
        sendMessage(groupId, groupName, receiverType, popup);

    } else if (type == 'Leave') {
        leave(groupId, groupName);

    } else if (type == 'ChangeOwnership') {
        changeOwnership(groupId, groupName);

    } else if (type == 'GroupDelete') {
        groupDelete(groupId, groupName);

    }else if (type == 'EditMembers'){
        //alert(groupName);
        window.location.href = groupName;
    }
});
/**
 * leaving group
  */

function leave(groupId, groupName) {
    $.popup.confirm({
        title: 'Leave Group',
        content: 'Are you sure you want to disconnect from '+groupName+' ?',
        okButtonText: 'Ok', 
        cancelButtonText: 'Cancel', // По дефолту "Cancel"
        onOk: function() {
            $.ajax({
                  url: '/groups/leaveGroup',
                  type : 'POST',
                  data :{
                            'groupid'     : groupId
                        },
                  success  : function(data){
                                $('tr[groupid="'+groupId +'"]').hide();
                            },
                  dataType : "json"
            });
        },
        onCancel: function() {} // в любом случае, в обоих случая окно конфирма закрывает. В алерте тоже
    });
}

function sendMessage(groupId, groupName, receiverType, popup) {
    $('#groupId').val(groupId)
    $('#receiverType').val(receiverType)
    $('#receiverName').text(groupName)
    $('#' + popup).show();
}

$('.popup-close').live('click', function(e){
    e.preventDefault();
    $(".popup").hide();
})
/*
$('#SendMessageForm').live('submit',function() {

    $(this).ajaxSubmit({
        type : 'post',
        clearForm: true,
        url : '/groups/sendMessage',
        dataType : "json",
        success: function(data) {
            $(".popup").hide();
        }
    });
    return false;
});*/
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
                        url : '/groups/sendMessage',
                        dataType : "json",
                        success: function(data) {
                            $(".popup").hide();
                        }
                    });
            }
        });



function groupDelete(groupId, groupName) {
    var action = 'delete';

    $.popup.confirm({
        title: 'Group '+groupName ,
        content: 'Are you sure you want to permanently remove from Pommelo?', // думаю, не так часто понадобится. Хватит и заголовка "Sure you want to
        okButtonText: 'Ok', // Текст кнопки. По дефолту "Ok"
        cancelButtonText: 'Cancel', // По дефолту "Cancel"
        onOk: function() {
            $.ajax({
                  url: '/groups/deleteGroup',
                  type : 'POST',
                  data :{
                            'groupid'     : groupId
                        },
                  success  : function(data){
                                $('.QuickGroupAccess[groupid="'+groupId +'"]').hide();
                                $('tr[groupid="'+groupId +'"]').hide();
                                $.popup.alert({title: groupName+'has been deleted.', content: ''});
                            },
                  dataType : "json"
            });
        },
        onCancel: function() {} // в любом случае, в обоих случая окно конфирма закрывает. В алерте тоже
    });
}

function changeOwnership(groupId, groupName) {

    $("#ChangeOwnershipPopup").show();

    $("#ChangeOwnershipPopup .GroupName").attr("groupid", groupId);
    $("#ChangeOwnershipPopup .GroupName").attr("groupName", groupName);
    $("#ChangeOwnershipPopup .GroupName").text(groupName+' members');
    $.ajax({
        url: "/groups/getMembersAjax",
        type : 'POST',
        data :{
            'groupid' : groupId
        },
        success  : function(data){

                    var block;
                    $("#ChangeOwnershipPopup .GroupContainer").html("");
                    $(data).each(function(item){
                        var imgSrc = '';
                        if (this.PhotoLink != null){
                            imgSrc = '/images/user/small/'+this.PhotoLink;
                        }else{
                            imgSrc = '/images/templates/avatar.gif';
                        }
                        block = '<div class="data-item c popup-data-item AdminCandidate" companyid="'+this.companyId+'">'
                                    +'<div class="l">'
                                    +'<input class="mt20" type="checkbox" companyName="'+this.companyName+'" value="'+this.companyId+'" name="AdminCandidate">'
                                    +'</div>'
                                    +'<div class="image l">'
                                      +'<img src="'+imgSrc+'"  width="60" height="60" alt="" />'
                                    +'</div>'
                                    +'<div class="content r">'
                                      +'<h4><a href="/company/'+this.companyId+'">'+this.companyName+'</a></h4>'
                                      +'<div class="description">'
                                        +this.fullAddress
                                      +'</div>'
                                    +'</div>'
                                    +'<div class="content r">'
                                    +'</div>'
                                  +'</div>';
                        $("#ChangeOwnershipPopup .GroupContainer").append(block);
                    })
                  },
        dataType : "json"
    });
}

$('input[name="AdminCandidate"]').live('click',function(e){
    //e.preventDefault();
    console.log('hello world');
    $('input[name="AdminCandidate"]').attr('checked', false);
    $(this).attr('checked', true);
})
$("#btnMakeAdmin").on('click',function(e){
    e.preventDefault();
    if ($('input[name="AdminCandidate"]').is(':checked')){
        var companyId = $('input[name="AdminCandidate"]').val();
        var companyName = $('input[name="AdminCandidate"]').attr('companyName');
        //alert(companyId );
        var groupName = $("#ChangeOwnershipPopup .GroupName").attr("groupname");
        $.popup.confirm({
            title: 'Group '+groupName,
            content: 'Are you sure you want to change group ownership of '
                      + groupName +'?',
            okButtonText: 'Ok',
            cancelButtonText: 'Cancel',
            onOk: function() {
                $("#ChangeOwnershipPopup").hide();
                $.ajax({
                      url: '/groups/changeOwnership',
                      type : 'POST',
                      data :{
                                'groupId'     : $("#ChangeOwnershipPopup .GroupName").attr("groupid"),
                                'companyId'   : companyId,
                                'leave'       : $('input[name="LeaveBool"]').is(':checked')
                      },
                      success  : function(data){
                                    var contentText = companyName+' is now the new group administrator of '+groupName+'.';
                                    if ($('input[name="LeaveBool"]').is(':checked')){
                                        contentText = 'You are no longer a member of '+groupName+'.'+titleText;
                                    }
                                    $.popup.confirm({
                                        title: 'Confirmation message',
                                        content: contentText,
                                        okButtonText: 'Ok',
                                        cancelButtonText: 'Cancel',
                                        onOk: function() {window.location.reload(true); },
                                        onCancel: function() {window.location.reload(true);}
                                    });
                                },
                      dataType : "json"
                });
            },
            onCancel: function() {} // в любом случае, в обоих случая окно конфирма закрывает. В алерте тоже
        });
    }
    if (!$('input[name="AdminCandidate"]').is(':checked') && $('input[name="LeaveBool"]').is(':checked') ){
        $.popup.confirm({
            title: 'Error',
            content: 'You can\'t leave unless making smbd admin.', // думаю, не так часто понадобится. Хватит и заголовка "Sure you want to
            okButtonText: 'Ok', // Текст кнопки. По дефолту "Ok"
            cancelButtonText: 'Cancel', // По дефолту "Cancel"
            onOk: function() { },
            onCancel: function() {} // в любом случае, в обоих случая окно конфирма закрывает. В алерте тоже
        });
    }

});

})

