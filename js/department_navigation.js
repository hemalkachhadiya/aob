

/**
 * clicking on option list item
 */
$('.ParentDepartment').live('dblclick',function(e){
    e.preventDefault();
    //alert('');
    var container = $(this).parent();
    var idParent = $(this).val();
    $('.ParentDepartmentChangeHeader').html($(this).html());
    $('.ParentDepartmentChangeLink').parent().show();

    getChildDepartment(container,idParent);
})
$('.ChildDepartment').live('dblclick',function(e){
    e.preventDefault();
    var search = '';
    var department = $(this).val();
    var container = $('.DepartmentList');
    var targetContainer = $('#find-freelancer').attr('targetContainer');
    var additionalContainers = $('#find-freelancer').attr('additionalContainers');
    if (targetContainer != ''){ // fork of addding department in form edit
        if (targetContainer == 'department'){
            window.location.href = '/department/'+$('.DepartmentList option:selected').val()+'/1';
        }
        console.log($('.DepartmentList option:selected'));
        $('.DepartmentList option:selected').each(function(){
            var ability = true;
            var currentValue = $(this).val();
            $(targetContainer).find('input').each(function(){
                if ($(this).val() == currentValue){
                    ability = false;
                }
            });
            if (additionalContainers != ''){ // if set additional container
                $(additionalContainers ).find('input').each(function(){
                    if ($(this).val() == currentValue){
                        ability = false;
                    }
                });
            }
            var inputName = 'DepartmentList[]';
            if (inputName != ''){
                inputName = $('#find-freelancer').attr('inputName');
            }
            if (ability){
                $(targetContainer).append(   '<input class="HiddenDepartment" type="hidden" value="'+$(this).val()+'" name="'+inputName+'">'
                                            +'<span>'+$(this).html()+'<span class="delete" id_department="'+$(this).val()+'">×</span></span>')
            }
        });
    }else{ // searching freelancer fork
        $.ajax({
                url: '/main/searchUser',
                type : 'POST',
                data : {
                    word : search,
                    id_department : department
                },
                success  : function(data){
                            console.log(data);
                            $(container).html('');
                            $(data).each(function(){
                                $(container).append('<option value="'+this.id+'" class="SearchedUser">'+this.firstName+' '+this.lastName+'</option>');
                            })
                          },
                dataType : "json"
            });
    }

})
$('#ChangeListButton').live('click',function(e){
    e.preventDefault();
    var Container = $('.DepartmentList option:selected');
    Container.trigger('dblclick');
    if (Container.hasClass('ChildDepartment')){
        $('.close').trigger('click');
    }
})
$('.ParentDepartmentChangeLink').live('click',function(e){
    e.preventDefault();
    getParentDepartments('.DepartmentList');
    $('.ParentDepartmentChangeLink').parent().hide();
    $('.ParentDepartmentChangeHeader').html('Выберите область знаний');
    $('.ParentDepartmentChangeHeader').attr('department','');
})
function setParentId(id){
    $('input[name="parentId"]').val(id);
}
/**
 * getting last level of departments
 * @param container
 * @param idParent
 */
function getChildDepartment(container,idParent)
{
    $.ajax({
            url: '/profile/getChildDepartments',
            type : 'POST',
            data : {
                idParent : idParent,
                search : $('#ParentDepartmentUserSearch').val()
            },
            success  : function(data){
                        console.log(data);
                        $('.ParentDepartmentChangeLink').parent().show();
                        $(container).html('');
                        $(data).each(function(){
                            $(container).append('<option value="'+this.id+'" class="ChildDepartment">'+this.name+'</option>');
                        })
                      },
            dataType : "json"
        });
    setParentId(idParent);
}

function getParentDepartments(container){
    $.ajax({
            url: '/profile/getParentDepartments',
            type : 'POST',
            data : {
                search : $('#ParentDepartmentUserSearch').val()
            },
            success  : function(data){
                        console.log(data);
                        $(container).html('');
                        $(data).each(function(){
                            $(container).append('<option class="ParentDepartment" value="'+this.id+'">'+this.name+'</option>');
                        })
                      },
            dataType : "json"
        });
    setParentId('');
}
/**
 * searching department through the input
 */
$('#ParentDepartmentUserSearch').live('keyup',function(e){
    e.preventDefault();
    var searchWord = $(this).val();
    container = $('.DepartmentList');

    if($('input[name="parentId"]').val() == '' ){
        console.log('parent search')
        getParentDepartments(container);
    }else{
        console.log($('input[name="parentId"]').val())
        console.log('child search')
        getChildDepartment(container,$('input[name="parentId"]').val());
    }


    /*$( '.DepartmentList option' ).each(
      function()
      {
        var sourceString = $( this ).html();
        var isFound = sourceString.search(searchWord );
          console.log($( this ).html()+isFound);
        if (isFound>0){
            $( this ).show();
        }   else{
            $( this ).hide();
        }
      }
    );*/

/*

     $.ajax({
            url: '/profile/getChildDepartmentsAutocomplete',
            type : 'POST',
            data : {
                search : searchWord
            },
            success  : function(data){

                        console.log(data);
                        $('.ParentDepartmentChangeHeader').html('');
                        $(container).html('');
                        $(data).each(function(){
                            $(container).append('<option value="'+this.id+'" class="ChildDepartment">'+this.name+'</option>');
                        })
                      },
            dataType : "json"
        });
*/
    $('.ParentDepartmentChangeLink').parent().show();
})
$('.SearchedUser').live('click',function(e){
    e.preventDefault();
    window.location.href = '/profile/'+$(this).val();
})
$('.searchFreelancer,.choseDepartment').live('click',function(e){
    e.preventDefault();
    var container = $('#find-freelancer .DepartmentList');
    $('#find-freelancer').attr('targetContainer',$(this).attr('targetContainer'));
    $('#find-freelancer').attr('additionalContainers',$(this).attr('additionalContainers'));
    $('#find-freelancer').attr('inputName',$(this).attr('inputName'));
    getParentDepartments(container);
    showPopup('#find-freelancer')
    //$().show();
})
$(".delete").live('click',function(e){
    e.preventDefault();
    var container = $(this).parent('span');
    var value = $(this).attr('id_department');
    container.hide();
    $('.HiddenDepartment[value="'+value+'"]').remove();
})