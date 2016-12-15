<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>CRUD - C</title>
    <link rel="stylesheet" href="/src/css/common.css"/>
    <link rel="stylesheet" href="/src/css/write.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/src/ckeditor/ckeditor.js"></script>
    <script src="/src/js/cookie.js"></script>
    <script src="/src/js/write.js"></script>
</head>
<body>
    <div id="wrap">
        <div id="writeForm">
            <div class="wrap_writeForm">
                <input id="board_name" type="hidden" <?php echo 'value="' . $_GET[ 'board' ] . '"'?>/>
                <div class="input_box">
                    <span class="input_name">글쓴이</span><input name="writer" id="writer" type="text" disabled="disabled" value="<?php echo $_SESSION[ 'valid' ]; ?>"/>
                </div>
                <div class="input_box">
                    <span class="input_name">제목</span><input name="title" id="title" type="text" maxlength="35" autocomplete="off"/>
                </div>
                <div id="editor">
                    <textarea name="ckeditor" id="ckeditor" rows="10" cols="50"></textarea>
                    <script>
                        CKEDITOR.replace( 'ckeditor' );
                    </script>
                </div>
                <div class="form_button_wrap">
                    <button id="back">이전화면</button>
                    <button id="submit">글쓰기</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>