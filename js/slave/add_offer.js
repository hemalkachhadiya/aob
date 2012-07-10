$(document).ready(function(e){
    jQuery.validator.addMethod("manageWorks", function(value, element, params) {
        //console.log($('.UserPortfolioContainer input').hasClass('PortfolioListInput'));
        var PortfolioList = [   parseInt($('select[name="PortfolioList[1]"] option:selected').val()),
                                parseInt($('select[name="PortfolioList[2]"] option:selected').val()),
                                parseInt($('select[name="PortfolioList[3]"] option:selected').val())];

        var result = true;
        if (PortfolioList.length > 2){
            for( var i = 0 ; i <PortfolioList.length; i++  ){
                for( var j = 0 ; j <PortfolioList.length; j++  ){
                    if (!isNaN(PortfolioList[i]) && !isNaN(PortfolioList[j]) && PortfolioList[j] == PortfolioList[i] && i != j){
                        result = false;
                    }
                }
            }
        }


        return result;

    }, jQuery.format(""));
    $("#ProjectOfferForm").validate(
    {
        rules: {
            comment : {
                required : true

            },
            account_to :{
                manageWorks :true,
                //required : true,
                number : true
            },
            account_from :{
               // required :  true,
                number : true
            },
            time_to :{
               // required : true,
                number : true
            } ,
            time_from :{
               // required : true,
                number : true
            }//,
            //id_time_type : true
        },
        messages:{

            comment : {
                required : 'Комментарий обязателен для заполнения'

            },
            account_to :{
                //required : '"Бюджет до" обязательно для заполнения',
                manageWorks : 'Вы не можете прикрепить две одинаковые работы.',
                number : '"Бюджет до" может быть только целым числом'
            },
            account_from :{
                //required :  '"Бюджет от" обязателен для заполнения',
                number : '"Бюджет от" может быть только целым числом'
            },
            time_to :{
                //required : '"Время выполнения до" обязательно для заполнения',
                number : '"Время вполнения до" может быть только целым числом'
            } ,
            time_from :{
                //required : '"Время выполнения от" обязательно для заполнения',
                number : '"Время вполнения от" может быть только целым числом'
            }//,
            //id_time_type : 'Единицы измирения времени не указаны'
        },
        errorPlacement: function(error, element)
        {
            var errorHolder = $("#ProjectOfferForm .errorHolder");
            error.appendTo( errorHolder );
            //alert(error);
            console.log(error);
        }

    });
})