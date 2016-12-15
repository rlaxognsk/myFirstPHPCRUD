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
        echo '<li><a href="/write/?board=' . $board . '" class="article_write">글쓰기</a></li>';

        if ( isset( $_SESSION[ 'is_admin' ] ) && $_SESSION[ 'is_admin' ] ) {
            echo '<li><a class="article_delete" href="javascrit: void();">글삭제</a></li>';
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
}
