var POFOL = POFOL || {};

POFOL.modify = {

    init: function () {

        this.getData();
        this.submit();
        
    },
    getData: function () {
        var board_name = POFOL.utils.getQueryString().board;
        var board_number = POFOL.utils.getQueryString().no;

        $.ajax( {
            url: '/modify/get_text.php?board=' + board_name + '&no=' + board_number,
            method: 'get',
            dataType: 'text'
        } )
        .done( function ( req ) {

            req = JSON.parse( req );

            if ( req.valid ) {
                $( '#title' ).val( req.title );
                CKEDITOR.instances.ckeditor.setData( req.text );
                $( '#wrap' ).show();
            }
            else {
                alert( req.error );
                location.href = location.href.replace( 'modify', 'read' );
            }
        } )
        .fail( function () {
            alert( '정보를 가져오지 못했습니다.' );
            location.href = location.href.replace( 'modify', 'read' );
        } );
    },

    submit: function () {

        var $submit = $( '#submit' );

        $submit.on( 'click.modify', function () {

            var editorData = {
                title: $( '#title' ).val(),
                text: CKEDITOR.instances.ckeditor.getData()
            };

            if ( editorData.title.search( /\S/ ) === -1 ) {

                alert( '제목을 입력해주세요.' );
                $( '#title' ).trigger( 'focus' );
                return false;
            }

            $( 'button' ).prop( 'disabled', true );

            $.ajax( {
                url: './modify.php',
                method: 'post',
                data: editorData,
                dataType: 'text'
            } )
            .done( function ( req ) {
                req = JSON.parse( req );

                if ( req.valid ) {
                    // alert( req.message );
                    location.href = location.href.replace( 'modify', 'read' );
                }
                else {
                    alert( req.error );
                    $( 'button' ).prop( 'disabled', false );
                }
            } )
            .fail( function () {
                alert( '요청 실패.' );
                $( 'button' ).prop( 'disabled', 'false' );
            } );
        } );
    }    
};

$( function () {
    POFOL.modify.init();
} );