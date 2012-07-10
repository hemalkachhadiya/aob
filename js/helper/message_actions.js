/* mark as read unread read and move to trash */

$(".ajaxAction").live('click',function(e){

      var dataList = getCheckedMessages();
      // way to work with one message begin

      var tmpDataList = new Array();
      var messageId = $('.mailbox-message').attr('messageid');
      if (messageId){
        tmpDataList.push(messageId);
        dataList = tmpDataList;
      }
      // way to work with one message end

      console.log(dataList);

      var action = $(this).attr('action');

      var usertype = $(this).attr('usertype');
      $.ajax({
          url: '/mailbox/'+action+'Mail',
          type : 'POST',

          data :{
                    'userType'    : usertype,
                    'data'        : dataList
                },
          success  : function(data){
                      if (action=="markRead"){
                          $('#MailList tr').each(function(item){
                              var CurrentMessage = $(this);
                              console.log(dataList);
                              for(var i=0; i<dataList.length; i++) {
                                if (CurrentMessage.attr('letterid') == dataList[i]) {
                                    CurrentMessage.removeClass('unread');
                                    CurrentMessage.addClass('read');
                                }
                              }

                          })
                      }
                      if (action=="markUnread"){
                          $('#MailList tr').each(function(item){
                              var CurrentMessage = $(this);
                              console.log(dataList);
                              for(var i=0; i<dataList.length; i++) {
                                if (CurrentMessage.attr('letterid') == dataList[i]) CurrentMessage.addClass('unread');
                              }

                          })
                      }
                      if (action=="delete"){
                          $('#MessageContainer tr').each(function(item){
                              var CurrentMessage = $(this);
                              console.log(dataList);
                              for(var i=0; i<dataList.length; i++) {
                                if (CurrentMessage.attr('letterid') == dataList[i]){
                                    CurrentMessage.remove();
                                }
                              }
                          })
                          var setIdentifier = '';
                          var setAddIdentifier = 'was';
                          if (dataList.length != 1) {
                              setIdentifier = 's';
                              setAddIdentifier = 'were';
                          }
                          $.popup.alert({title: dataList.length+' message'+setIdentifier+' '+setAddIdentifier+' deleted.'});
                      }
                    },
          dataType : "json"
      });
      //console.log(data);
  })
  /**
   *
   * get all message's id where checkbox is checked
   */
  function getCheckedMessages(){
      var mainMailContainer = $("#MessageContainer");
      var MessageList = new Array();
      mainMailContainer.find('input:checked').each(function(item){
          messageId = $(this).parents("tr").attr('letterId');
          MessageList.push(messageId);
      })
      console.log(MessageList);
      return MessageList;

  }
