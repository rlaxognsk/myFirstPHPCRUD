var POFOL = POFOL || {};

POFOL.read = {

    init: function () {

        this.delete();
    },

    delete: function () {

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
                    location.href = POFOL.cookie.get( 'prevPage' );
                } )
                .fail( function () {
                    alert( '삭제 요청이 처리되지 않았습니다.' );
                } );
            }
        } );
    }
};

$( function () {
    POFOL.read.init();
} );