var POFOL = POFOL || {};

POFOL.auth = {

    init: function () {

        document.cookie = 'prevPage=' + location.href + '; path=/';
    }
    
};

$( function () {
    POFOL.auth.init();
} );
