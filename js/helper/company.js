
            /**
             * setting commentTemplate
             */
            function getCommentTemplate(objComment){
                    if (objComment.PhotoLink){
                        avatarImg = '/images/user/small/'+objComment.PhotoLink;
                    }else
                    {
                        avatarImg = '/images/templates/avatar.gif'
                    }
                    blockDiv =
                                '<div class="data-item c post-item wall-post-item">'
                                +'<div class="image l">'
                                +'<img src="'+avatarImg+'" alt="" width="40" height="40"/>'
                                +'</div>'
                                +'<div class="data l">'
                                +   '<div class="title"><a href="">'+objComment.companyName+'</a></div>'
                                +   '<div class="info">'+objComment.createDate+'</div>'
                                + '</div>'
                                + '<div class="content l">'
                                +   objComment.commentText
                                + '</div>'
                                + '<div class="dropdown dropdown-hover">'
                                +   '<a href="" class="dropdown-toggle knob"></a>'
                                +   '<ul class="dropdown-menu">'
                                +     '<li><a href="">Report abuse</a></li>'
                                +     '<li><a href="">Delete</a></li>'
                                +   '</ul>'
                                + '</div>'

                                +'</div>';
                return blockDiv;

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
                    //console.log(this.commentText)
                    //console.log(mainCommentsContainer);
                    blockDiv  = getCommentTemplate(this);

                    mainCommentsContainer.append(blockDiv);
                })

            }
            /**
             * function to load comments
             */
            function loadComments(){
                var moreCoefficient = $("#CommentListButtonMore a").attr('moreCoefficient');
                var dashboardOwner = $("#CommentListButtonMore a").attr('dashboardOwner');

                $.ajax({
                        url: '/dashboardfront/getcomments',
                        type : 'POST',

                        data :{
                                  'moreCoefficient'    : moreCoefficient,
                                  'dashboardOwner'     : dashboardOwner
                              },
                        success  : function(data){
                                        $("#CommentListButtonMore a").attr('moreCoefficient',++moreCoefficient);
                                        //console.log(data['parentComments']);
                                        postParentComments(data['parentComments']);

                                        //$(data['parentComments']).each()
                                  },
                        dataType : "json"
                    });
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
                        }
                    });
                    return false;
                });



            });