$('.MutualConnection').live('click',function(e){
            e.preventDefault();
            $("#MutualPopup").show();
            var companyList = $(this).attr('companylist');
            $.ajax({
                url: "/connectionsfront/getMutualConnections",
                type : 'POST',
                data :{
                    'idList' : companyList
                },
                success  : function(data){
                            var block;
                            $("#MutualContainer").html("");
                            $(data).each(function(item){
                                console.log(this);
                                var imgSrc = '';
                                if (this.PhotoLink != null){
                                    imgSrc = '/images/user/small/'+this.PhotoLink;
                                }else{
                                    imgSrc = '/images/templates/avatar.gif';
                                }
                                block = '<div class="data-item c popup-data-item">'
                                            +'<div class="image l">'
                                              +'<img src="'+imgSrc+'"  width="60" height="60" alt="" />'
                                            +'</div>'
                                            +'<div class="content r">'
                                              +'<h4><a href="/company/'+this.companyId+'">'+this.companyName+'</a></h4>'
                                              +'<div class="description">'
                                                +this.fullAddress
                                              +'</div>'
                                            +'</div>'
                                          +'</div>';
                                $("#MutualContainer").append(block);

                            })
                          },
                dataType : "json"
            });
        })