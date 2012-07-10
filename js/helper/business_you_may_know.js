$('#SeeMoreSameBusiness').live('click',function(e){
            e.preventDefault();
            $("#GroupPopup").show();
            var groupId = $(this).attr('groupid');
            var groupName =  $(this).attr('groupname');
            $("#GroupName").text(groupName+' members');
            $.ajax({
                url: "/dashboardfront/getBusinessYouMayKnow",
                type : 'POST',
                data :{
                    'groupid' : groupId
                },
                success  : function(data){
                            console.log(data);
                            var block;
                            $("#GroupContainer").html("");
                            appendMembers(data['SameBusiness']);
                          },
                dataType : "json"
            });
        })
function appendMembers(list){
    $(list).each(function(item){
        var imgSrc = '';
        if (this.PhotoLink != null){
            imgSrc = '/images/user/small/'+this.PhotoLink;
        }else{
            imgSrc = '/images/templates/avatar.gif';
        }
        block = '<div class="data-item c popup-data-item RemovableItem"  companyid="'+this.companyId+'" >'
                    +'<div class="image l">'
                      +'<img src="'+imgSrc+'"  width="60" height="60" alt="" />'
                    +'</div>'
                    +'<div class="content r">'
                      +'<h4><a href="/company/'+this.companyId+'">'+this.companyName+'</a></h4>'
                      +'<div class="description">'
                        +this.stateTitle+', '+this.City+','+this.StreetAddress
                      +'</div>'
                      +'<a href=""'
                         +'class="add knob ConnectionAction"'
                         +'popup="ConnectPopup"'
                         +'action="connect"'
                         +'companyname="'+this.companyName+'"'
                         +'companyid="'+this.companyId+'"'
                         +'userid="'+this.userId+'"  >add</a>'
                    +'</div>'
                  +'</div>';
        $("#GroupContainer").append(block);

    })
}