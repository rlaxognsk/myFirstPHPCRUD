<?php
session_start();
if ( isset( $_SESSION[ 'valid' ] ) ) {
    header( 'Location: /' );
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>CRUD</title>
    <link rel="stylesheet" href="/src/css/common.css" />
    <link rel="stylesheet" href="/src/css/login.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/src/js/utils.js"></script>
    <script src="/src/js/login.js"></script>
</head>
<body>
    <div id="wrap">
        <div id="loginBox">
            <div class="wrap_loginBox">
                <form action="/login/check.php" id="login" name="login" method="post">
                    <div class="input_wrap">
                        <div class="login_input">
                            <input type="text" id="loginID" name="user_id" placeholder="ID" maxlength="12"/>
                        </div>
                        <div class="login_input">
                            <input type="password" id="loginPass" name="user_pass" placeholder="PASSWORD" maxlength="20"/>
                        </div>
                        <p class="login_failed">ID 혹은 비밀번호가 잘못되었습니다.</p>
                        <input type="submit" id="submit" value="로그인" />
                    </div>
                    <div class="etc_wrap">
                        <a href="/join">회원가입</a>
                        <a href="/">메인</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>