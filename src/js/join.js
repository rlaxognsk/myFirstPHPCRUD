var POFOL = POFOL || {};

POFOL.join = {

    init: function () {

        this.idValid = false;
        this.passValid = false;
        this.passCheckValid = false;
        this.emailValid = false;

        this.idValidCheck();
        this.passValidCheck();
        this.emailValidCheck();
        this.submit();
    },

    idValidCheck: function () {

        var that = this;

        var $id = $( '#user_id' ),
            $id_valid = $id.next(),
            sto = null;

        $id.on( 'keyup', function () {

            if ( this.value.length < 4 || this.value.length > 20 ) {

                $id_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '아이디는 4 ~ 20자여야 합니다.';
                that.idValid = false;
                return false;
            }
            else if ( this.value.search( /\W/ ) !== -1 ) {

                $id_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '아이디는 영문자, 숫자, _ 만 가능합니다.';
                that.idValid = false;
                return false;
            }

            $id_valid.css( 'color', '#000' )[ 0 ].innerHTML = '사용 여부 확인중...';
            clearTimeout( sto );
            sto = setTimeout( function () {

                $.ajax( {

                    url: '/join/id_check.php',
                    method: 'post',
                    data: 'user_id=' + $id.val(),
                    dataType: 'text'
                } )
                    .done( function ( req ) {

                        if ( req === 'ok' ) {
                            $id_valid.css( 'color', '#00f' )[ 0 ].innerHTML = '사용 가능한 아이디입니다.';
                            that.idValid = true;
                        }
                        else {
                            $id_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '사용 불가능한 아이디입니다.';
                            that.idValid = false;
                        }
                    } )
                    .fail( function () {
                        $id_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '사용 불가능한 아이디입니다.';
                        that.idValid = false;
                    } );

            }, 250 );
        } );
    },

    passValidCheck: function () {

        var that = this;

        var $pass = $( '#user_pass' ),
            $pass_check = $( '#user_pass_check' ),
            $pass_valid = $pass.next(),
            $pass_check_valid = $pass_check.next();

        $pass.on( 'keyup' , function () {

            if ( this.value.length < 4 || this.value.length > 20 ) {

                $pass_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '비밀번호는 4 ~ 20자리여야 합니다.';
                that.passValid = false;
                return false;
            }

            $pass_valid.css( 'color', '#00f' )[ 0 ].innerHTML = '사용 가능한 비밀번호입니다.';
            that.passValid = true;
            return true;
            
        } );

        $pass_check.on( 'keyup' , function () {

            if ( this.value !== $pass.val() ) {
                $pass_check_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '비밀번호가 같지 않습니다.';
                that.passCheckValid = false;
                return false;
            }
            else {
                $pass_check_valid.css( 'color', '#00f' )[ 0 ].innerHTML = '확인되었습니다.';
                that.passCheckValid = true;
                return true;
                
            }
        } );

    },

    emailValidCheck: function () {

        var that = this;

        var $email = $( '#user_email' ),
            $email_valid = $email.next();

        var sto = null;

        $email.on( 'keyup', function () {

            clearTimeout( sto );
            if ( this.value.search( /.+@.+\..+/ ) === -1 ) {

                $email_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '사용 불가능한 email형식 입니다.';
                that.emailValid = false;
                return false;
            }

            $email_valid.css( 'color', '#000' )[ 0 ].innerHTML = '이메일 사용여부 확인중...';

            sto = setTimeout( function () {
                $.ajax( {

                    url: 'email_check.php',
                    method: 'post',
                    data: 'email=' + $email.val(),
                    dataType: 'text'
                } )
                    .done( function ( req ) {

                        if ( req === 'ok' ) {

                            $email_valid.css( 'color', '#00f' )[ 0 ].innerHTML = '사용 가능한 email입니다.';
                            that.emailValid = true;
                            return true;
                        }
                        else {

                            $email_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '사용 불가능한 email입니다.';
                            that.emailValid = false;
                            return false;
                        }
                    } )
                    .fail( function () {
                        $email_valid.css( 'color', '#f00' )[ 0 ].innerHTML = '사용 불가능한 email입니다.';
                        that.emailValid = false;
                        return false;
                    } );
            }, 250 );
        } );
    },

    alertTrigger: function ( type ) {

        switch ( type ) {
            case 'id':
                alert( '아이디를 다시 확인해 주세요.' );
                $( '#user_id' ).trigger( 'focus' );
                break;
            
            case 'pass':
                alert( '비밀번호를 다시 확인해 주세요.' );
                $( '#user_pass' ).trigger( 'focus' );
                break;
            
            case 'passCheck':
                alert( '비밀번호를 다시 확인해 주세요.' );
                $( '#user_pass_check' ).trigger( 'focus' );
                break;

            case 'email':
                alert( '이메일을 다시 확인해 주세요.' );
                $( '#user_email' ).trigger( 'focus' );
                break;
            
            case 'error':
                alert( '서버에서 오류가 발생하였습니다. 잠시 후 다시 시도해주세요.' );
                break;

            default:
                break;
        }

    },
    submit: function () {

        var that = this;
        var $join = $( '#join' );
        
        $join.on( 'submit', function ( e ) {

            if ( !that.idValid ) {
                that.alertTrigger( 'id' );
                return false;
            }
            if ( !that.passValid ) {

                that.alertTrigger( 'pass' );
                return false;
            }
            if ( !that.passCheckValid ) {

                that.alertTrigger( 'passCheck' );
                return false;
            }
            if ( !that.emailValid ) {

                that.alertTrigger( 'email' );
                return false;
            }

            e.preventDefault();
            
            var formData = {
                user_id: $( '#user_id' ).val(),
                user_pass: $( '#user_pass' ).val(),
                user_pass_check: $( '#user_pass_check' ).val(),
                user_email: $( '#user_email' ).val()
            };

            $.ajax( {

                url: 'check.php',
                data: formData,
                dataType: 'text',
                method: 'post'

            } )
                .done( function ( req ) {

                    if ( req === 'ok' ) {

                        alert( '회원가입이 완료되었습니다.' );
                        var prevPage = document.cookie.match( /prevPage=(\S*)(?:$|;)/ );
                        location.href = prevPage !== null ? prevPage[ 1 ] : '/';
                        return true;

                    }
                    else if ( req === 'id' ) {
                        that.alertTrigger( 'id' );
                        return false;
                    }
                    else if ( req === 'pass' ) {
                        that.alertTrigger( 'pass' );
                        return false;
                    }
                    else if ( req === 'email' ) {
                        that.alertTrigger( 'email' );
                        return false;
                    }
                    else if ( req === 'error' ) {
                        that.alertTrigger( 'error' );
                        return false;
                    }
                } )
                .fail( function () {

                    that.alertTrigger( 'error' );
                    return false;
                } );

        } );
    }
};

$( function () {
    POFOL.join.init();
} );