var POFOL = POFOL || {};

POFOL.write = {

    init: function () {

        this.prevPage();
        this.submit();
    },

    prevPage: function () {

        var $prev = $( '#back' );

        $prev.on( 'click.write', function () {

            var prevPage = POFOL.cookie.get( 'prevPage' );
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
                    if ( req === 'ok' ) {
                        var pageMove = '/?board=' + POFOL.utils.getQueryString().board;
                        location.href = pageMove;
                    }
                } )
                .fail( function () { 
                    alert( req );
                    $( 'button' ).prop( 'disabled', false );
                } );
        } );
    }
    
};

$( function () {

    POFOL.write.init();
    
} );