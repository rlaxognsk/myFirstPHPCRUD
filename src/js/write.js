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

            $( 'button' ).attr( 'disabled' , 'disabled' );

            $.ajax( {
                url: 'write.php',
                method: 'post',
                data: editorData,
                dataType: 'text'
            } )
                .done( function ( req ) { 
                    if ( req === 'ok' ) {
                        var prevPage = POFOL.cookie.get( 'prevPage' )
                        location.href = prevPage !== null ? prevPage : '/';
                    }
                } )
                .fail( function () { 
                    alert( req );
                    $( 'button' ).removeAttr( 'disabled' );
                } );
        } );
    }
    
};

$( function () {

    POFOL.write.init();
    
} );