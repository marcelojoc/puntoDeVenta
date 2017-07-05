

var geoCliente ={

    nav :null,
    lat:"",
    lon:"",
    strMessage:"",

 requestPosition:  function() {
        if (geoCliente.nav == null) {
            geoCliente.nav = window.navigator;
        }

        var geoloc = geoCliente.nav.geolocation;
        if (geoloc != null) {
            geoloc.getCurrentPosition(geoCliente.successCallback, geoCliente.errorCallback);
        }

    },

    successCallback:  function(position) {

            geoCliente.lat= position.coords.latitude;

            geoCliente.lon= position.coords.longitude;
    },

    errorCallback: function(error) {
        // Check for known errors
        switch (error.code) {
            case error.PERMISSION_DENIED:
                geoCliente.strMessage = "Access to your location is turned off. "  +
                    "Change your settings to turn it back on.";
                break;
            case error.POSITION_UNAVAILABLE:
                geoCliente.strMessage = "Data from location services is " + 
                    "currently unavailable.";
                break;
            case error.TIMEOUT:
                geoCliente.strMessage = "Location could not be determined " +
                    "within a specified timeout period.";
                break;
            default:
                break;
        }
    }





}