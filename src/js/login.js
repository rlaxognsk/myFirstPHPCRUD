var POFOL = {
    
    init: function () {
        
        this.inputFocus();
        this.formCheck();
    },
    
    inputFocus: function () {
        
        $( '.login_input input' ).on( {
            focus: function () {
                $( this ).parent().addClass( 'focus' );
            },

            blur: function() {
                $( this ).parent().removeClass( 'focus' );
            }
        } );
    },
    
    formCheck: function () {
        
        var $loginForm = $( '#login' ),
            $loginID = $( '#loginID' ),
            $loginPass = $( '#loginPass' ),
            $loginFail = $( '.login_failed' ),
            $loginBtn = $( '#submit' );
            
        
        
        $loginForm.submit( function ( e ) {
            
            var postData = {
                user_id: $loginID.val(),
                user_pass: $loginPass.val()
            };
            
            e.preventDefault();
            $loginFail.css( 'visibility', 'hidden' );
            
            if ( postData.user_id === '' ) {
                
                alert( '아이디를 입력해주세요. ' );
                $loginID.trigger( 'focus' );
                return false;
            }
            
            if ( postData.user_pass === '' ) {
                
                alert( '비밀번호를 입력해주세요.' );
                $loginPass.trigger( 'focus' );
                return false;
            }
            
            $loginBtn
                .attr( 'value', '로그인중...')
                .addClass( 'active' );
            
            $.ajax( {
                
                url: '/login/check.php',
                method: 'POST',
                data: postData,
                dataType: 'text',
                success: function ( req ) {
                    
                    if ( req === 'success' ) {
                        location.href = '/';
                    }
                    else {
                        $loginFail.css( 'visibility', 'visible' );
                        $loginBtn
                            .attr( 'value', '로그인' )
                            .removeClass( 'active' );
                    }
                }
                
            } );
        } );
    }
};

$( function () {
    POFOL.init();
} );