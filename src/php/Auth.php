<?php

class Auth
{
    public static function loginOut()
    {

        if ( isset( $_SESSION[ 'valid' ] ) ) {
            echo '<ul class="auth">';
            echo '<li>' . $_SESSION[ 'valid' ] . '님, 안녕하세요.</li>';
            echo '<li><a href="/logout/logout.php" class="auth_logout">로그아웃</a></li>';
            echo '</ul>';
        }
        else {
            echo '<ul class="auth">';
            echo '<li><a href="/join" class="auth_join">회원가입</a></li>';
            echo '<li><a href="/login" class="auth_login">로그인</a></li>';
            echo '</ul>';
        }
    }

    public static function articleManage()
    {
        if ( !isset( $_SESSION[ 'valid' ] ) ) {
            exit;
        }

        $board = isset( $_GET[ 'board' ] ) ? $_GET[ 'board' ] : 'main';
        echo '<ul class="article_manage">';
        echo '<li><button data-href="/write/?board=' . $board . '" class="article_write">글쓰기</button></li>';

        if ( isset( $_SESSION[ 'is_admin' ] ) && $_SESSION[ 'is_admin' ] ) {
            echo '<li><button class="article_delete">글삭제</button></li>';
        }
        echo '</ul>';
    }

    public static function articleModify()
    {
        if ( !isset( $_SESSION[ 'valid' ] ) ) {
            exit;
        }

        echo '<ul class="article_modify">';
        echo '<li><a href="/modify" class="article_modified">수정</a></li>';
        echo '<li><a href="/delete" class="article_delete">삭제</a></li>';
        echo '</ul>';
    }

    public static function writeComment()
    {
        if ( !isset( $_SESSION[ 'valid' ] ) ) {
            exit;
        }

        echo '<form id="commentSend" method="post" action="./comment.php">';
            echo '<p>댓글달기</p>';
            echo '<div class="write_wrapper">';
                echo '<span class="comment_writer">' . $_SESSION[ 'valid' ] . '</span>';
                echo '<textarea id="commentText" name="comment_text" maxlength="200"></textarea>';
                echo '<input id="submit" type="submit" value="등록" />';
            echo '</div>';
        echo '</form>';
    }
}
