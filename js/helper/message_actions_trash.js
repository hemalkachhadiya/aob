/* mark as read unread read and move to trash */
function trashAction(action,usertype,dataList){
    $.ajax({
              url: '/mailbox/'+action+'Mail',
              type : 'POST',
              data :{
                        'userType'    : usertype,
                        'messageList' : dataList
                    },
              success  : function(data){

                          if (action=="restore" || action =="deletePermanently"){
                              $('#MessageContainer tr').each(function(item){
                                  var CurrentMessage = $(this);

                                  for(var i=0; i<dataList.length; i++) {
                                    if (CurrentMessage.attr('letterid') == dataList[i]['id']) CurrentMessage.remove();
                                  }
                              })
                          }
                        },
              dataType : "json"
          });
}
$(".ajaxAction").live('click',function(e){

      var dataList = getCheckedMessages();


      var messageId = $('.mailbox-message').attr('messageid');
      if (messageId){

        dataList.push({id: messageId, userType: $(this).attr('usertype')});
      }

      console.log(dataList);

      var action = $(this).attr('action');

      var usertype = $(this).attr('usertype');
    if (action =="deletePermanently"){
        $.popup.confirm({
            title: 'Permanently Delete',
            content: 'Do you wish to permanently delete '+dataList.length+' messages?',
            okButtonText: 'Ok',
            cancelButtonText: 'Cancel', // По дефолту "Cancel"
            onOk: function() {
                trashAction(action,usertype,dataList);
            },
            onCancel: function() {} // в любом случае, в обоих случая окно конфирма закрывает. В алерте тоже
        });
    }else{
        trashAction(action,usertype,dataList);
    }

      //console.log(data);
  })
  /**
   *
   * get all message's id where checkbox is checked
   */
  function getCheckedMessages(){
      var mainMailContainer = $("#MessageContainer");
      var MessageList = new Array();
      var jsonMessage = [];
      mainMailContainer.find('input:checked').each(function(item){
          var messageId = $(this).parents("tr").attr('letterId');
          console.log(messageId);
          MessageList[messageId] = $(this).parents("tr").attr('userType');
          jsonMessage.push({id: messageId, userType: $(this).parents("tr").attr('userType')});
      })
      console.log (jsonMessage);
      
      return jsonMessage;

  }
