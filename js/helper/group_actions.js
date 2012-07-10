$(".MembershipRequestAction").live('click',function(e){
  e.preventDefault();

  var dataList = getCheckedMessages();
  var action = $(this).attr('action');
  console.log(action);
  // way to work with one message begin
  console.log(dataList);

  $.ajax({
      url: '/groups/setMemberRequestAction',
      type : 'POST',

      data :{
                'action'      : action,
                'data'        : dataList
            },
      success  : function(data){
                    for(var i=0; i<dataList.length; i++) {
                        $('tr[requestid="'+dataList[i]+'"]').hide();
                    }
                },
      dataType : "json"
  });
})
/**
* get all message's id where checkbox is checked
*/
function getCheckedMessages(){
  var mainMailContainer = $("#MembershipRequestContainer");
  var MessageList = new Array();
  mainMailContainer.find('input:checked').each(function(item){
      messageId = $(this).parents("tr").attr('requestid');
      MessageList.push(messageId);
  })
  //console.log(MessageList);
  return MessageList;

}
