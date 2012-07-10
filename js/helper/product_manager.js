
// todo make with javascriptmvc
$(document).ready(function(){
    function saveSearchCategory($id){
        $.ajax({
            url: '/products/getCategoryTree',
            type : 'POST',
            data :{
                      'id' : $id
            },
            success  : function(data){
                console.log(data);
                $("#CategoryID").val(data.id);
                if (data.pCategory){
                    var currentCategory =    '<a href="">'+data.pCategory.name+'</a>'
                                        +'<a href="" class="selected">'+data.cCategory.name+'</a>';
                }else{
                    var currentCategory =  '<a href="" class="selected">'+data.cCategory.name+'</a>';
                }

               showCategory(currentCategory);

            },
            dataType : "json"
        });
    }

    function saveCategory(categoryId,fCategory,sCategory){
       $("#CategoryID").val(categoryId);
       if (sCategory){
        var currentCategory =  '<a href="">'+$('#PCategory').find('option:selected').text()+'</a>'
                              +'<a href="" class="selected" >'+$('#PPCategory').find('option:selected').text()+'</a>';
       }else{
        var currentCategory =  '<a href="" class="selected" >'+$('#PCategory').find('option:selected').text()+'</a>';
       }

                              //+'<a class="selected" href="">'+$('#PPPCategory').find('option:selected').text()+'</a>'

       showCategory(currentCategory);
    }
    function showCategory(currentCategory){
        $('.CategoryPlaceHolder').show();
        $('.breadcrumb-links').html(currentCategory);
        $("#btnDownloadTemplate").show();
    }

    $('#PCategory').live('change',function(e){
        e.preventDefault();

        var parentID = $(this).val();
        saveCategory(parentID,true,false);
        // todo loading bar
        $.ajax({
            url: '/products/getCategoriesByParentID',
            type : 'POST',
            data :{
                      'table'           : 'products_categories',
                      'parentID'        : parentID
                  },
            success  : function(data){
               $('#PPCategory option').remove();
        //       $('#PPPCategory option').remove();
               var OptionList;
               $(data).each (function (e){
                   OptionList += '<option value="'+$(this).attr ('id')+'">'+$(this).attr ('name')+'</option>';
               })
               $('#PPCategory').append(OptionList);
            },
            dataType : "json"
        });

    });


    $('#PPCategory').live('change',function(e){
        e.preventDefault();
        var parentID = $(this).val();
        saveCategory(parentID,true,true);
        // todo loading bar
        /*$.ajax({
            url: '/products/getCategoriesByParentID',
            type : 'POST',
            data :{
                      'table'           : 'products_categories',
                      'parentID'        : parentID
                  },
            success  : function(data){

               $('#PPPCategory option').remove();
               var OptionList;
               $(data).each (function (e){
                   OptionList += '<option value="'+$(this).attr ('id')+'">'+$(this).attr ('name')+'</option>';
               })
               $('#PPPCategory').append(OptionList);
            },
            dataType : "json"
        });*/

    });

    /**
     * saving category via ajax
     */
    /*$('#PPPCategory').live('click',function(e){
       e.preventDefault();
       $("#CategoryID").val($(this).val());
       var currentCategory =  '<a href="">'+$('#PCategory').find('option:selected').text()+'</a>'
                              +'<a href="">'+$('#PPCategory').find('option:selected').text()+'</a>'
                              +'<a class="selected" href="">'+$('#PPPCategory').find('option:selected').text()+'</a>'

       $('.CategoryPlaceHolder').show();

       $('.breadcrumb-links').html(currentCategory);
    }); */
    $('#CategoryRemove').live('click',function(e){
       e.preventDefault();
       $('.CategoryPlaceHolder').hide();
       $("#CategoryID").val('');
       $("#btnDownloadTemplate").hide();
    });

    function getSearchResults(){
        var word = $('#SearchCategory').val();
        if (word != '' ){
            $.ajax({
                url: '/products/getProductCategories',
                type : 'POST',
                data :{
                          'word' : word
                },
                success  : function(data){
                   console.log(data);
                   $('#SearchHolder option').remove();
                   var OptionList;
                   $(data).each (function (e){
                       OptionList += '<option value="'+$(this).attr ('id')+'">'+$(this).attr ('name')+'</option>';
                   })
                   $('#SearchHolder').append(OptionList);
                },
                dataType : "json"
            });
        }
    }
    $('#SearchCategoryButton').live('click', function(e){
        e.preventDefault();
        getSearchResults();
    });
    $('#SearchCategory').live('keyup',function(e){
        $("#CategoryContainer").hide();
        $("#SearchCategoryContainer").show();
        getSearchResults();
        
    });
    $('#SearchCategory').live('keypress', function(e) {
      if (e.keyCode == 13) {
        return false;
      }
    });

    $('#SearchHolder').live('click',function(e){
        e.preventDefault();
        var id = $('#SearchHolder').val();
        saveSearchCategory(id);
        /*
        $.ajax({
            url: '/products/getCategoryTree',
            type : 'POST',
            data :{
                      'id' : $('#SearchHolder').val()
            },
            success  : function(data){
                $("#CategoryID").val(data.id);

                var currentCategory =  '<a href="">'+data.PCategory+'</a>'
                                  +'<a href="">'+data.PPCategory+'</a>'
                                  +'<a class="selected" href="">'+data.PPPCategory+'</a>'
               $('.CategoryPlaceHolder').show();
               $('.breadcrumb-links').html(currentCategory);

            },
            dataType : "json"
        });*/
    })

    $('.navigationButton').live('click',function(e){
        e.preventDefault();

        $(".container-block-step").each(function(){
            $(this).hide();
        })
        $('#'+$(this).attr('navigation_target')).show();
    });

    $('#CategoryViewSwitcher').live('click',function(e){
        e.preventDefault();
        $("#SearchCategoryContainer").hide();
        $("#CategoryContainer").toggle();
    })
});