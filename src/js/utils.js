var POFOL = POFOL || {};

POFOL.utils = {

    getQueryString: function () {

        var url = location.href;
        var regexp = new RegExp( /[?&]([\w-]*)=?([\w-]*)?/g );
        var search = null;
        var ret = {};

        while ( ( search = regexp.exec( url ) ) !== null ) {
            ret[ search[ 1 ] ] = search[ 2 ];
        }

        return ret;
    },

    getCookie: function ( prop ) {

        var reg = new RegExp( prop + '=(\\S*)(?:$|;)' );
        var result = document.cookie.match( reg );

        if ( result === null ) {
            return result;
        }
        else {
            return result[ 1 ];
        }
    },

    setCookie: function ( prop, value ) {

        document.cookie = prop + '=' + value + '; path=/';
    }
};