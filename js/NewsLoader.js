$(document).ready(function(){
    function loadItems(page){
        var ItemType = $('#MoreNews').attr('type');
        $.ajax({
                url: '/main/getAjaxNewsList',
                type : 'POST',
                data :{
                        page : page,
                    ItemType : ItemType
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
                            +'<a href="'+entity.link+'" title="">'+entity.title+'</a>'
                            +'<p>'+entity.shortBody+'</p>'
                            +'<span class="date">'+entity.createTime+'</span>'
                        +'</div>';
        return template;
    }


})
