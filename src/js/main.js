var POFOL = POFOL || {};

POFOL.main = {

    init: function () {

        this.write();
        this.delete();

    },

    write: function () {

        var $write = $( '.article_write' );

        $write.on( 'click', function () {

            location.href = $( this ).data( 'href' );
        } );
    },

    delete: function () {

        var $delete = $( '.article_delete' );
        var $allDelete = $( 'th.a_delete input' );

        $delete.on( 'click', function () {

            var items = [];
    
            $( 'td.a_delete input:checked' ).each( function () {
                var number = $( this ).parent().siblings( '.bd_num' )[ 0 ].innerHTML;
                items.push( number );
            } );

            if ( items.length === 0 ) {
                window.alert( '삭제할 게시물을 선택해 주세요.' );
                return false;
            }
            
            var confirm = window.confirm( items.length + '개의 게시글을 삭제하시겠습니까?' );

            if ( confirm ) {

                var query = POFOL.utils.getQueryString();

                var data = {
                    board_name: query.board,
                    numbers: items.join()
                };

                $.ajax( {
                    url: '/read/deletes.php',
                    method: 'post',
                    data: data,
                    dataType: 'text'
                } )
                .done( function ( req ) {
                    alert( req );
                    location.href = location.href;
                } )
                .fail( function () {
                    alert( '전송 오류.' );
                } );

            }

        } );

        $allDelete.on( 'click', function () {

            if ( $( this ).is( ':checked' ) ) {
                $( '.a_delete input' ).prop( 'checked', true );
            }
            else {
                $( '.a_delete input' ).prop( 'checked', false );
            }

        } );
    }
};

$( function () {

    POFOL.main.init();

} );