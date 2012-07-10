function changeButtonProfile(button){
    button.attr("id","ConnectButton");
    button.attr("popup","ConnectPopup");
    button.removeClass('red ConnectionAction');
    button.addClass('orange');
    button.html('Connect');
}
function hideItems(button){
    button.closest("tr").hide();
}
function changeButton(button){
    button.attr("popup","ConnectPopup");
    button.removeClass('red ');
    button.attr('action','connect')
    button.addClass('orange');
    button.html('Connect');
}
function disconnect(button,callback){
    $.popup.confirm({
        title: 'Connection dialog',
        content: 'Would you like to disconnect', // думаю, не так часто понадобится. Хватит и заголовка "Sure you want to
        okButtonText: 'Ok', // Текст кнопки. По дефолту "Ok"
        cancelButtonText: 'Cancel', // По дефолту "Cancel"
        onOk: function() {
            var url = '/connectionsfront/'+button.attr("action")+'Connection';
            $.ajax({
                url: url,
                type : 'POST',
                data :{
                    'id' : button.attr("connectionid")
                },
                success  : function(data){
                                switch (callback){
                                    case "hideItems" : hideItems(button);
                                    break;
                                    case "changeButton" : changeButton(button); break;
                                    case "changeButtonProfile" : changeButtonProfile(button); break;
                                }

                                //button.parents("tr").hide();
                          },
                dataType : "json"
            });
        },
        onCancel: function() {} // в любом случае, в обоих случая окно конфирма закрывает. В алерте тоже
    });
}
