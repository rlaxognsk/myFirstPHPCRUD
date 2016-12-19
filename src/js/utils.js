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
    }
};