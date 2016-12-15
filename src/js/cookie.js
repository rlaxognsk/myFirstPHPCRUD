var POFOL = POFOL || {};

POFOL.cookie = {

    set: function ( prop, value ) {

        document.cookie = prop + '=' + value + '; path=/';
    },

    get: function ( prop ) {

        var reg = new RegExp( prop + '=(\\S*)(?:$|;)' );
        var result = document.cookie.match( reg );

        if ( result === null ) {
            return result;
        }
        else {
            return result[ 1 ];
        }
    },
    
};