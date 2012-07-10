        function closePopups(){
            $(".popup").hide();
        }
        $('.mr5').live('click',function(e){
            e.preventDefault();
            closePopups();
            var popupId = $(this).attr('popup');
            $('#'+popupId).show();
        })
        $('.ml5').live('click',function(e){
            e.preventDefault();
            closePopups();
        })
        $('.knob').live('click',function(e){
            e.preventDefault();
            closePopups();
        })
