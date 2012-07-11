$(document).ready(function(){
    function loadItems(page){
        $.ajax({
                url: '/main/getAjaxNewsList',
                type : 'POST',
                data :{
                        page : page
                      },
                success  : function(data){
                            var container = $('#NewsContainer');
                            console.log(data['list']);
                            $(data['list']).each(function(){
                                var block = getTemplate(this);
                                container.append(block);
                                console.log(block);
                            });
                            if (data['isMore'] == false){
                                $('#MoreNews').hide();
                            }else{
                                $('#MoreNews').attr('page',parseInt($('#MoreNews').attr('page'))+1)
                            }
                            console.log(data);
                          },
                dataType : "json"
            });
    }


    $('#MoreNews').live('click',function(e){
        e.preventDefault();
        var page = $(this).attr('page');
        loadItems(page);
    })
    $('#MoreNews').trigger('click');


    function getTemplate(entity){

        var template = '<div class="news">'
                            +'<a href="/news_item?id='+entity.id+'" title="">'+entity.title+'</a>'
                            +'<p>'+entity.shortBody+'</p>'
                            +'<span>'+entity.createTime+'</span>'
                        +'</div>';
        return template;
    }

})