$(document).ready(function(e){
    jQuery.validator.addMethod("department", function(value, element, params) {
        console.log($('#DepartmentListPortfolio input').hasClass('HiddenDepartment'));
        if ($('#DepartmentListPortfolio input').hasClass('HiddenDepartment'))
            return true;
        else
            return false;
    }, jQuery.format(""));
    jQuery.validator.addMethod("shopFile", function(value, element, params) {
        var state = true;
        console.log ($('input[name="shop"]').is(':checked') );
        if ($('input[name="shop"]').val() == 1){
            if ($('input[name="workFile"]').val() == '' ){
                state = false
            }
            console.log(state);
        }
        return state;
    }, jQuery.format(""));

    $("#AddWorkForm").validate(
    {
        rules: {
            title: {
                required : true
                //department : true
            },
            description : "required",
            duration : {
                required : true,
                number : true
            },
            id_type :  {
                required : true,
                department : true
            },
            price : {
                required : true,
                number : true
            },
            id_time_type : 'required',
            shop : {
                shopFile : true
            }
        },
        messages:{
            title : {
                required : 'Заголовок обязателен для заполнения'
                //department : 'Добавте дисциплину'
            },
            description : 'Описание обязательно для заполнения',
            duration :{
                required : 'Время выполнения обязательно для заполнения',
                number : 'Время вполнения может быть только целым числом'
            } ,
            id_type :{
                required :  'Тип обязателен для заполнения',
                department : 'Добавте дисциплину'
            },
            price : {
                required : 'Цена обязательна для заполнения',
                number : 'Цена может быть только целым числом'
            },
            id_time_type : 'Единицы измирения времени',
            shop :{
                shopFile : 'Прикрепите файл для того, что бы разместить работу в магазине'
            }
        },
        errorPlacement: function(error, element)
        {
            var errorHolder = $("#AddWorkForm .errorHolder");
            error.appendTo( errorHolder );
            //alert(error);
            console.log(error);
        }
    });
})