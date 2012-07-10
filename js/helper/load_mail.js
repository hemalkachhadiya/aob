function getMailTemplate(letter){
    if (letter.PhotoLink){
        avatarImg = '/images/user/small/'+letter.PhotoLink;
    }else
    {
        avatarImg = '/images/templates/avatar.gif'
    }
    var letterClass = '';
    if (letter.readFlag == 1) {
       letterClass = "read";
    }
    else
    {
        letterClass = "unread";
    }
    var letterType = $("#MailPager").attr('type');
    var letterPrefix = '';
    if (letter.userType ) {
        letterType = letter.userType;
        if (letter.userType == "receiver") {
            letterPrefix = '<b>From: </b>';
        }
        else
        {
            letterPrefix = '<b>To: </b>';
        }
    }
    blockDiv =  '<tr class="'+letterClass+'" letterId="'+letter.id+'" userType="'+letterType+'">'
                +'<td class="checkbox"><input type="checkbox"></td>'
                +'<td>'+letterPrefix+letter.CompanyName+'</td>'
                +'<td class="theme"><a href="/mail/read/'+letterType+'/'+letter.id+'">'+letter.topic+'</a></td>'
                +'<td>'+letter.intime+'</td>'
                +'</tr>';
    return blockDiv; 
}
function postMail(mailList,mailAmount){
    var mainMailContainer = $("#MessageContainer");
    var blockDiv;

    console.log(mailList);
    if (mailList){
        $(mailList).each(function(item){
            blockDiv  = getMailTemplate(this);

            mainMailContainer.append(blockDiv);
        })
    }else if (!mailAmount){
         mainMailContainer.append("<tr><td colspan='4'>0 messages</td></tr>");
    }
}
function clearMessageContainer(){
    var mainMailContainer = $("#MessageContainer");
    mainMailContainer.html('');
}
function refreshPaging(mailAmount,currentPage){
  var Container = $("#MailPager");
  var rowsPerPage = 10;
  var pageAmount = 0;
  if (!mailAmount){
      Container.hide();
  }else{
      Container.html('');
      var modAmount = (parseInt(mailAmount,10) % rowsPerPage);
      var tmp = mailAmount - modAmount - rowsPerPage;
      if ( tmp >= 0 && mailAmount != rowsPerPage){
         pageAmount =  (mailAmount - modAmount) / rowsPerPage + 1;
         //console.log(currentPage);
         if (currentPage != 1){
            var prevPage =  currentPage-1;
            block = '<a href="" page="'+prevPage+'" class="PageItem prev" ><</a>';
            Container.append(block);
         }
         for (var i =1 ; i<(pageAmount+1) ;i++){
            var block ='';
            if (currentPage == i ){
                block = '<a href="" page="'+i+'" class="PageItem selected" >'+i+'</a>';
            }else{
                block = '<a href="" page="'+i+'" class="PageItem " >'+i+'</a>';
            }
            Container.append(block); 
         }
         //console.log(pageAmount+' '+currentPage);
         if(currentPage != pageAmount) {
            var nextPage =  currentPage+1;
            block = '<a href="" page="'+(nextPage)+'" class="PageItem next" >></a>';
            Container.append(block);
         }

      }else{
         pageAmount = 1; 
      }


  }

}
/**
 * sorting titles
 * @param page
 */
$('.MessageSortable').live('click',function(e){
    e.preventDefault();
    $('input[name="SortableParam"]').val($(this).attr('name'));
    loadMail(1);
})
/**
 * function to load comments
 */
function loadMail(page){

    //var moreCoefficient = $("#CommentListButtonMore a").attr('morecoefficient');
    var moreCoefficient = page;
    // identifier to understand whose actions we are doing
    var letterType = $("#MailPager").attr('type');
    console.log($('input[name="SortableParam"]').val());
    $.ajax({
            url: '/mailbox/getmail',
            type : 'POST',

            data :{
                      'moreCoefficient'    : moreCoefficient,
                      'type'               : letterType,
                      'order'              : $('input[name="SortableParam"]').val(),
                      'messageFlag'        : $('#messageFlag').val()
                  },
            success  : function(data){

                            console.log(data);
                            //console.log(data['parentComments']);
                            clearMessageContainer();
                            postMail(data['MailList'],data['MailAmount']);
                            refreshPaging(data['MailAmount'],page);
                            //$("#CommentListButtonMore a").attr('moreCoefficient',++moreCoefficient);


                      },
            dataType : "json"
        });
}


$(document).ready(function(e){
    loadMail(1); // loading first ten messages for preview
    $('.PageItem').live('click', function(e){
        e.preventDefault();
        var page = parseInt($(this).attr('page'),10);
        loadMail(page);

    })

});