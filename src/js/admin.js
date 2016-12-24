var POFOL = POFOL || {};

POFOL.admin = {

    init: function () {
        this.addBoard();
        $( 'head' ).append( '<link rel="stylesheet" href="/src/css/admin.css"/>' );
    },

    addBoard: function () {
        
        var $boardList = $( '.board_list' );
        var pos = {
            x: ( $boardList.position().left + $boardList.width() + 5 ) + 'px',
            y: $boardList.position().top + 'px'
        };
        var $adminAddBoard = $( '.admin_add_board' );
        var $addBoardName = $( '#addBoardName' );

        $adminAddBoard.css( { top: pos.y, left: pos.x } );

        $( '.add_board' ).on( 'click', function () {
            if ( $( this ).hasClass( 'active' ) ) {
                $( this ).removeClass( 'active' );
                $adminAddBoard.hide();

            }
            else {
                $( this ).addClass( 'active' );
                $adminAddBoard.show();
                $addBoardName.trigger( 'focus' );
            }
        } );

        $addBoardName.on( 'keypress', function ( e ) {

            if ( e.keyCode === 13 ) {
                $( '#addBoardOK' ).trigger( 'click' );
            }

        } );
        
        $( '#addBoardOK' ).on( 'click', function () {
            var newBoardName = $addBoardName.val();

            if ( newBoardName.search( /\s|\W/ ) >= 0 ) {
                alert( '게시판 이름은 공백이 아닌 영어, 숫자, _ 만 가능합니다.' );
                return false;
            }
            if ( newBoardName.length > 10 ) {
                alert( '게시판 이름은 10글자까지만 허용합니다.' );
                return false;
            }

            $.ajax( {
                url: '/admin/add_board.php',
                data: 'board_name=' + newBoardName,
                dataType: 'text',
                method: 'post'
            } )
            .done( function ( req ) {
                req = JSON.parse( req );

                if ( req.valid ) {
                    alert( req.message );
                    location.href = location.href;
                }
                else {
                    alert( req.error );
                }
            } )
            .fail( function () {
                
                alert( '서버로부터 응답이 없습니다.' );
            } );
        } );
    }
};

$( function () {
    POFOL.admin.init();
} );