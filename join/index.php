<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>JOIN</title>
    <link rel="stylesheet" href="/src/css/common.css"/>
    <link rel="stylesheet" href="/src/css/join.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/src/js/join.js"></script>
</head>
<body>
    <div id="wrap">
        <div id="joinBox">
            <div id="wrap_joinBox">
                <h1>회원가입</h1>
                <div id="wrap_joinForm">
                    <form action="check.php" method="post" id="join" name="join">
                        <div class="input_wrap">
                            <div class="join_input">
                                <input id="user_id" name="user_id" type="text" placeholder="아이디" maxlength="20" autocomplete="off"/>
                                <p class="valid_id"></p>
                            </div>
                            <div class="join_input">
                                <input id="user_pass" name="user_pass" type="password" placeholder="비밀번호" maxlength="20" autocomplete="off"/>
                                <p class="valid_pass"></p>
                            </div>
                            <div class="join_input">
                                <input id="user_pass_check" type="password" placeholder="비밀번호 확인" maxlength="20" autocomplete="off"/>
                                <p class="valid_pass_check"></p>
                            </div>
                            <div class="join_input">
                                <input id="user_email" name="user_email" type="text" placeholder="이메일" maxlength="30" autocomplete="off"/>
                                <p class="valid_email"></p>
                            </div>
                            <div class="join_input" style="margin-top: 15px;">
                                <input type="submit" value="가입"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>