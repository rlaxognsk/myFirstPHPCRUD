var POFOL = POFOL || {};

POFOL.write = {

    init: function () {

        this.prevPage();
        this.submit();
        $( '#title' ).trigger( 'focus' );
    },

    prevPage: function () {

        var $prev = $( '#back' );

        $prev.on( 'click.write', function () {

            var prevPage = POFOL.utils.getCookie( 'prevPage' );
            location.href = prevPage !== null ? prevPage : '/';
        } );
    },

    submit: function () {

        var $submit = $( '#submit' );

        $submit.on( 'click.write', function () {

            var editorData = {
                board_name: $( '#board_name' ).val(),
                article_title: $( '#title' ).val(),
                article_text: CKEDITOR.instances.ckeditor.getData()
            };

            if ( editorData.article_title.search( /\S/ ) === -1 ) {
                
                alert( '제목을 입력해주세요.' );
                $( '#title' ).trigger( 'focus' );
                return false;

            }

            $( 'button' ).prop( 'disabled' , true );

            $.ajax( {
                url: 'write.php',
                method: 'post',
                data: editorData,
                dataType: 'text'
            } )
                .done( function ( req ) {
                    req = JSON.parse( req );

                    if ( req.valid ) {
                        var pageMove = '/?board=' + POFOL.utils.getQueryString().board;
                        location.href = pageMove;
                    }
                    else {
                        alert( req.error );
                        $( 'button' ).prop( 'disabled', false );
                    }
                } )
                .fail( function () { 
                    alert( '글 작성에 실패했습니다.' );
                    $( 'button' ).prop( 'disabled', false );
                } );
        } );
    }
    
};

$( function () {

    POFOL.write.init();
    
} );