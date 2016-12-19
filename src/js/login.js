var POFOL = POFOL || {};
POFOL.login = {
    
    init: function () {
        
        this.inputFocus();
        this.formCheck();
        $( '#loginID' ).trigger( 'focus' );
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
            $loginBtn = $( '#submit' ),
            $inputs = $( 'input' );

        var that = this;
            
        
        
        $loginForm.submit( function ( e ) {
            
            var postData = {
                user_id: $loginID.val(),
                user_pass: $loginPass.val()
            };
            
            $inputs.attr( 'disabled', 'disabled' );
            e.preventDefault();
            $loginFail.css( 'visibility', 'hidden' );
            
            if ( postData.user_id === '' ) {
                
                alert( '아이디를 입력해주세요. ' );
                $inputs.removeAttr( 'disabled' );
                $loginID.trigger( 'focus' );

                return false;
            }
            
            if ( postData.user_pass === '' ) {
                
                alert( '비밀번호를 입력해주세요.' );
                $inputs.removeAttr( 'disabled' );
                $loginPass.trigger( 'focus' );
                return false;
            }

            $inputs.attr( 'disabled', 'disabled' );
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
                        // var prevPage = ( document.cookie ).match( /prevPage=(\S*)(?:$|;)/ );
                        var prevPage = POFOL.cookie.get( 'prevPage' );
                        location.href = prevPage !== null ? prevPage : '/';
                    }
                    else {
                        $loginFail.css( 'visibility', 'visible' );
                        $inputs.removeAttr( 'disabled' );
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
    POFOL.login.init();
} );