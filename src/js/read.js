var POFOL = POFOL || {};

POFOL.read = {

    init: function () {

        this.readComment( 1 );
        this.pagingComment();
        this.articleDelete();
        this.addComment();
        this.deleteComment();
    },

    articleDelete: function () {

        $( '.a_delete' ).on( 'click', function ( e ) {
            e.preventDefault();
            var is_delete = confirm( '정말 삭제하시겠습니까? ' );

            if ( is_delete ) {

                var query = POFOL.utils.getQueryString();

                var data = {
                    board_name: query.board,
                    board_number: query.no
                };

                $.ajax( {
                    url: '/read/delete.php',
                    method: 'post',
                    data: data,
                    dataType: 'text'
                } )
                .done( function ( req ) {
                    req = JSON.parse( req );

                    if ( req.valid ) {
                        location.href = '/?board=' + query.board;
                    }
                    else {
                        alert( req.error );
                    }
                    
                } )
                .fail( function () {
                    alert( '삭제 요청이 처리되지 않았습니다.' );
                } );
            }
        } );
    },

    readComment: function ( page ) {
        var articleID = $( '#articleID' ).val();
        var commentList = $( '.read_comment' )[ 0 ];

        $.ajax( {
            url: './comment_read.php?id=' + articleID + '&page=' + page,
            dataType: 'html',
            method: 'get'
        } )
        .done( function ( req ) {
            if ( req !== 'x' ) {
                commentList.innerHTML = req;
            }
            else {
                commentList.innerHTML = '댓글 정보를 받아오지 못했습니다.';
                console.log( '실패' );
            }
        } )
        .fail( function () {
            commentList.innerHTML = '댓글 정보를 받아오지 못했습니다.';
        } );
    },

    pagingComment: function () {
        
        var that = this;
        var $read_comment = $( '.read_comment' );
        var pos_read_comment = $read_comment.offset().top;

        $read_comment.on( 'click', '.c_paging', function ( e ) {
            console.log( 'click' );
            e.preventDefault();
            var page = $( this ).data( 'page' );
            that.readComment( page );

            if ( $( window ).scrollTop() > pos_read_comment ) {
                $( 'html, body' ).animate( {
                    scrollTop: pos_read_comment + 'px'
                }, 500 );
            }

        } );
    },
    addComment: function () {

        var that = this;

        var $text = $( '#commentText' ),
            $submit = $( '#submit' );

        $text.on( 'keydown', function ( e ) {
            if ( e.keyCode === 13 ) {
                e.preventDefault();
                $submit.trigger( 'submit' );
            }
        } );

        $( '#commentSend' ).on( 'submit' , function ( e ) {
            
            e.preventDefault();
            
            var data = {
                parent_article: $( '#articleID' ).val(),
                comment_text: $text.val(),
            };

            if ( data.comment_text === '' ) {
                alert( '댓글 내용을 작성해주세요.' );
                return false;
            }

            $submit.prop( 'disabled', true );

            $.ajax( {
                url: './comment_add.php',
                method: 'post',
                data: data,
                dataType: 'text'
            } )
            .done( function ( req ) {
                
                req = JSON.parse( req );
                if ( req.valid ) {
                    that.readComment( 1 );
                    $text.val( '' );
                    $submit.prop( 'disabled', false );
                }
                else {
                    alert( '오류: ' + req.error );
                    $submit.prop( 'disabled', false );
                }
            } )
            .fail( function () {

                alert( '댓글 입력에 실패했습니다. - 응답 실패 ' );
                $submit.prop( 'disabled', false );
            } );

        } );
    },

    deleteComment: function () {

        var that = this;
        var is_progress = false;

        $( '.read_comment' ).on( 'click', '.c_delete a', function ( e ) {
            
            if ( is_progress ) {
                return false;
            }
            is_progress = true;

            var commentID = $( this ).parent().parent().data( 'commentId' );
            var confirm = window.confirm( '이 댓글을 정말 삭제하시겠습니까?' );

            if ( confirm === true ) {

                $.ajax( {
                    url: '/read/comment_delete.php',
                    method: 'post',
                    data: 'commentID=' + commentID,
                    dataType: 'text'
                } )
                .done( function ( req ) {
                    
                    req = JSON.parse( req );

                    if ( req.valid ) {
                        that.readComment();
                        is_progress = false;
                    }
                    else {
                        alert( req.error );
                        is_progress = false;
                    }
                } )
                .fail( function () {
                    alert( '요청 실패.' );
                    is_progress = false;
                } );
            }
        } );
    }
};

$( function () {
    POFOL.read.init();
} );