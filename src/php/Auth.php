<?php

class Auth
{
    public static function loginOut()
    {
        echo '<script src="/src/js/auth.js"></script>';

        if ( isset( $_SESSION[ 'valid' ] ) ) {
            echo '<ul class="auth">';
            echo '<li>' . $_SESSION[ 'valid' ] . '님, 안녕하세요.</li>';
            echo '<li><a href="/logout/logout.php" class="auth_logout">로그아웃</a></li>';
            echo '</ul>';
        }
        else {
            echo '<ul class="auth">';
            echo '<li><a href="/login" class="auth_login">로그인</a></li>';
            echo '<li><a href="/join" class="auth_join">회원가입</a></li>';
            echo '</ul>';
        }
    }
}
