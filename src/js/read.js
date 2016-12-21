var POFOL = POFOL || {};

POFOL.read = {

    init: function () {

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
                    alert( req );
                    location.href = '/?board=' + query.board;
                } )
                .fail( function () {
                    alert( '삭제 요청이 처리되지 않았습니다.' );
                } );
            }
        } );
    },

    addComment: function () {

        $text = $( '#commentText' ),
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
                url: './comment.php',
                method: 'post',
                data: data,
                dataType: 'text'
            } )
            .done( function ( req ) {

                if ( req === 'o' ) {

                    window.location.href = window.location.href;
                }
                else {
                    alert( '오류: ' + req );
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

        var is_progress = false;

        $( '.comment_list' ).on( 'click', '.c_delete a', function ( e ) {
            
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
                    if ( req === 'o' ) {
                        alert( '삭제 성공.' );
                        window.location.href = window.location.href;
                    }
                    else {
                        alert( '오류: ' + req );
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