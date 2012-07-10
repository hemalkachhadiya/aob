/*
 deleting and reporting abuse on dashboard
 */
 function clckAction(){
    //console.log('fire');
    var commentID = $(this).attr('commentId');
    var commentAction = $(this).attr('action');
    //console.log(commentAction+commentID)

     $.ajax({
            url: '/dashboardfront/'+commentAction,
            type : 'POST',
            data :{
                      'commentID'    : commentID,
                      'dashboardID' : $("#MainCommentForm").find('[name="dashboardID"]').val(),
                      'type'        : $("#MainCommentForm").attr('dashboardType')
                  },
            success  : function(data){
                        if (commentAction == 'delete'){
                            $('div[parentid="'+commentID+'"]').hide();
                        }
                        //$('div[parentid="'+commentID+'"]').hide();
                      },
            dataType : "json"
        });
 }
 //$('.CommentActions').bind('click',clckAction);


function getCommentActions(commentType,objComment){
    deleteAction = '';
    if ($('#MainCommentForm').attr('edit') == 'unlock' || $('#MainCommentForm').attr('userId') == objComment.authorID){
        var deleteAction = '<li><a href="#" class="CommentActions" action="delete" commentType="'+commentType+'" commentId="'+objComment.commentID+'" >Delete</a></li>';
    }
      var editBlock = '<div class="dropdown dropdown-toggle">'
          +'<a class="dropdown-toggle knob" href=""></a>'

          +'<ul class="dropdown-menu">'
            +'<li><a href="#" class="CommentActions" action="reportAbuse" commentType="'+commentType+'" commentId="'+objComment.commentID+'" >Report abuse</a></li>'
            +   deleteAction
          +'</ul>'
        +'</div>';

    return editBlock;
}

function getChildCommentTemplate(objComment){
    if (objComment.PhotoLink){
        avatarImg = '/images/user/small/'+objComment.PhotoLink;
    }else
    {
        avatarImg = '/images/templates/avatar.gif'
    }
    // parentId attr for selector purposes
    var editBlock = getCommentActions('child',objComment);

    //console.log(objComment);
 var block =

    '<div class="comment-item childComment" parentid="'+objComment.commentID+'">'

      +'<div class="data-item c  ">'
        +'<div class="image l">'
         + '<img alt="" src="'+avatarImg+'"  width="40" height="40" >'
        +'</div>'
        +'<div class="data l">'
         + '<div class="title"><a href="/company/' + objComment.companyId + '/">'+objComment.companyName+'</a></div>'
         +   '<div class="info">'+$.timeago(objComment.createDate)+'</div>'
        +'</div>'
        +'<div class="content l">'
         + objComment.commentText
        +'</div>'

        +editBlock

      +'</div>'

    +'</div>'



    return block;
}
/**
 * template for parent comments
 * setting parent commentTemplate
 */
function getCommentTemplate(objComment){
        if (objComment.PhotoLink){
            avatarImg = '/images/user/small/'+objComment.PhotoLink;
        }else
        {
            avatarImg = '/images/templates/avatar.gif'
        }
        var editBlock = getCommentActions('parent',objComment);
        blockDiv =
                    '<div class="parentComment data-item c post-item wall-post-item" parentid="'+objComment.commentID+'">'
                    +'<div class="image l">'
                    +'<img src="'+avatarImg+'" alt="" width="40" height="40"/>'
                    +'</div>'
                    +'<div class="data l">'
                    +   '<div class="title"><a href="/company/' + objComment.companyId + '/">'+objComment.companyName+'</a></div>'
                    +   '<div class="info">'+$.timeago(objComment.createDate)+'</div>'
                    + '</div>'
                    + '<div class="content l">'
                    +   objComment.commentText
                    + '</div>'
                    + editBlock
                    + '<div class="c"></div>'
                    + '<div class="comments data-block pv1" >'
                        + '<div class="comment-list data-block" >'
                        + '</div>'
                    + '</div>'
                    +'</div>';
    return blockDiv;

}
/**
 * form to add child comments
 * id of parent block
 */
function getChildCommentForm(parentId){
    var dashboardId = $('#MainCommentForm input[name="dashboardID"]').val(),
        randomId = 'commentForm' + (Math.round(Math.random() * 10000000));

    // console.log(randomId);

    block =   '<a href="" class="details-turner details-turner-comment js-details-turner" data-details-type="comment" data-details="#' + randomId + '">Comment</a>'
              +'<form method="POST" class="ChildCommentForm comment-form mt10" style="display: none" id="' + randomId + '">'
              +'<input type="hidden" name="dashboardID" value="'+dashboardId+'">'
              +'<input type="hidden" name="parentID" value="'+parentId+'">'
              +'<textarea class="expandable" name="content" ></textarea>'
              +'<div class="hint mb0">Press Enter to post your comment</div>'
            +'</form>';
    return block;
}
/**
 * add ability to add comments
 */
function postCommentAbility( parentComments ){
    var commentBlock = '';
    var mainCommentsContainer = $("#CommentList");

    $(parentComments).each(function(item){

        commentBlock = getChildCommentForm(this.commentID);
        $('[parentid="'+this.commentID+'"] .comments .comment-list').append(commentBlock);

    });

    $(window).trigger('commentsLoaded');
}

/**
 * function for adding child comments into
 * parent blocks
 * @param childComments
 */
function postChildComments(childComments){
    // console.log('child comments:'+childComments);
    $(childComments).each(function(item){
        console.debug(this.commentParentID);
        var ParentCommentContainer = $('[parentid="'+this.commentParentID+'"] .comments .comment-list');
        ParentCommentContainer.append(getChildCommentTemplate(this));
        //console.log(ParentCommentContainer );
    })


}
/**
 * posting parent comments' block
 * @param parentComments
 */
function postParentComments(parentComments){
    var mainCommentsContainer = $("#CommentList");
    var blockDiv;
    var avatarImg;
    $(parentComments).each(function(item){
        // console.log(this.commentText)
        // console.log(mainCommentsContainer);
        blockDiv  = getCommentTemplate(this);

        mainCommentsContainer.append(blockDiv);

    })

}

/**
 * function to load comments
 */
function loadComments(){
    var moreCoefficient = $("#CommentListButtonMore a").attr('moreCoefficient');
    var dashboardOwner  = $("#CommentListButtonMore a").attr('dashboardOwner');
    var dashboardType   = $("#MainCommentForm").attr('dashboardType');

    $.ajax({
            url: '/dashboardfront/getcomments',
            type : 'POST',

            data :{
                      'moreCoefficient'    : moreCoefficient,
                      'dashboardOwner'     : dashboardOwner,
                      'type'               : dashboardType
                      //'password'     : $("#password").val()
                  },
            success  : function(data){
                            $("#CommentListButtonMore a").attr('moreCoefficient',++moreCoefficient);
//                            console.log("morestaus"+data['moreStatus']);
                            postParentComments(data['parentComments']);
                            postChildComments(data['childComments'])
                            postCommentAbility(data['parentComments']);
                            if (data['moreStatus'] == false){
                              $("#CommentListButtonMore").hide();
                            }
                            var actions = $('.CommentActions');
                            actions.bind('click',clckAction);
                            // console.log($('.CommentActions'));
                      },
            dataType : "json"
        });
    // console.log("bind");

}
/**
 * adding parent comments to dashboard
 * @param commentText
 */
function addParentComment(commentText){
    var mainCommentsContainer = $("#CommentList ");
    mainCommentsContainer.prepend(commentText);
}




$(document).ready(function(e){
    // loading first part of comments
    loadComments();
    /**
     * event after clicking button "more"
     */
    $('#CommentListButtonMore').live('click', function(e){
        e.preventDefault();
        loadComments();

    })
    function validate(formData, jqForm, options) {
        for (var i=0; i < formData.length; i++) {
            if (!formData[i].value) {
                alert('do not post empty comment');
                return false;
            }
        }
        //alert('Both fields contain values.');
    }
    /**
     * adding new comment action
     */
    $('#MainCommentForm').submit(function() {

        $(this).ajaxSubmit({
            beforeSubmit: validate,
            type : 'post',
            clearForm: true,
            url : '/dashboardfront/addcomment',
            dataType : "json",
            success: function(data) {
                //
                // addParentComment(data['commentText']);
                var mainCommentsContainer = $("#CommentList");
                var block = getCommentTemplate(data['comment']);
                mainCommentsContainer.prepend(block);
                //adding from for creating child comments
                postCommentAbility(data['comment']);
                $('.CommentActions').bind('click',clckAction);
            }
        });

        return false;
    });
    /**
     * adding new child comment
     */
    $(".ChildCommentForm").live('submit',function(e){
        e.preventDefault();
        var currentCommentContainer = $(this).parents('.comments');
        var currentComment = $(this);
        if (currentComment.find('[name="content"]').val()){
            $.ajax({
                url: '/dashboardfront/addComment',
                type : 'POST',
                data :{
                          'parentID'    : currentComment.find('[name="parentID"]').val(),
                          'content'     : currentComment.find('[name="content"]').val(),
                          'dashboardID' : currentComment.find('[name="dashboardID"]').val(),
                          'type'        : $("#MainCommentForm").attr('dashboardType')
                      },
                success  : function(data){
                            var block = getChildCommentTemplate(data['comment']);
                            currentComment.find('[name="content"]').val('');
                            //console.log(data['comment']);
                            if (currentCommentContainer.find('.childComment:last').length){
                                currentCommentContainer.find('.childComment:last').after(block);
                            }
                            else{
                                $(block).insertBefore(currentCommentContainer.find('.details-turner'));
                            }
                            $('.CommentActions').bind('click',clckAction);
                          },
                dataType : "json"
            });
        }

    })

    $(".ChildCommentForm").live('keypress',function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13') {
            $(".ChildCommentForm").trigger('submit');
            $(this).find('textarea').blur();
            console.log($(this).find('textarea'));
            window.q = $(this).find('textarea');
            $(this).find('textarea')[0].blur();
            $(this).parents('.comments').find('.details-turner').show();
        }
    });
    /*$('.details-turner').bind('click',function(e){
        
        $(this).hide();
    })
    $(".ChildCommentForm").live('focusout',function(event) {
        $(this).parents('.comments').find('.details-turner').show();
    });*/



});
