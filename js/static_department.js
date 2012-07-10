    $(document).ready(function(e){

        function getParentDepartments(container){
            $.ajax({
                    url: '/profile/getParentDepartments',
                    type : 'POST',
                    success  : function(data){
                                console.log(data);
                                $('container').html('');
                                $(data).each(function(){
                                    $(container).append('<option class="ParentDepartment" value="'+this.id+'">'+this.name+'</option>');
                                })
                              },
                    dataType : "json"
                });
        }
        
        function getChildDepartment(container)
        {
            $.ajax({
                    url: '/profile/getDepartments',
                    type : 'POST',
                    success  : function(data){
                                console.log(data);
                                $('#DepartmentList').html('');
                                $(data).each(function(){
                                    $('#DepartmentList').append('<option value="'+this.id+'">'+this.name+'</option>');
                                })
                              },
                    dataType : "json"
                });
        }
        $('.choseDepartment').live('click',function(e){
            $('#popup-bg').toggle();
            $('.DepartmentPopup').show();
            //var departmentType = $(this).attr('departmentType');
            var container = $(this).parent().find('p');
            console.log(container);
            getChildDepartment(container);
        })
        $(".delete").live('click',function(e){
            e.preventDefault();
            var container = $(this).parent('span');
            var value = $(this).attr('id_department');
            container.hide();
            $('.HiddenDepartment[value="'+value+'"]').remove();
        })
        $('#DepartmentForm').validate({
            submitHandler: function(form) {
                var container = $('.DepartmentList');
                container.html('');
                console.log(container);
                $('#DepartmentList option:selected').each(function(){
                    container.append(   '<input class="HiddenDepartment" type="hidden" value="'+$(this).val()+'" name="DepartmentList[]">'
                                        +'<span>'+$(this).html()+'<span class="delete" id_department="'+$(this).val()+'">×</span></span>')
                });
                $('.DepartmentPopup').toggle();
                $('#popup-bg').toggle();
            }
        })
    })