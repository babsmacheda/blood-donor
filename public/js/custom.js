var map;
var marker;
var infoWindow;
var formWindow;
var messagewindow;
var donorwindow;
var markers = [];
var my_marker;

var first_name_input = $('input#first_name');
var last_name_input = $('input#last_name');
var blood_type_input = $('select#blood_type');

var donor_name = $("#info").find("#name");
var donor_type = $("#info").find("#donor_type");

var user_lat = null;
var user_lng = null;

var donor_form = $("div#form");
var donor_info = $("div#info");
var message_info = $("div#message");

var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 6.6092560, lng: 3.3437250},
        zoom: 15
        //mapTypeId: 'roadmap'
    });

    infoWindow = new google.maps.InfoWindow({
        content: document.getElementById('form')
    });

    formWindow = new google.maps.InfoWindow({
        content: document.getElementById('form')
    });

    messagewindow = new google.maps.InfoWindow({
        content: document.getElementById('message')
    });

    donorwindow = new google.maps.InfoWindow({
        content: document.getElementById('info')
    });

    google.maps.event.addListener(map, 'click', function(event) {
        marker = new google.maps.Marker({
            position: event.latLng,
            map: map
        });


        google.maps.event.addListener(marker, 'click', function () {
            formWindow.open(map, marker);
            donor_form.show();
            console.log("Saved: " + marker.getPosition());
        });

        google.maps.event.addListener(formWindow, "closeclick", function () {
            donor_form.hide();
            donor_info.hide();
        });
    });
}

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {

        user_lat = position.coords.latitude;
        user_lng = position.coords.longitude;
        getDonors();

        /*my_marker = new google.maps.Marker({
           position: {lat: user_lat, lng: user_lng},
           map: map,
            icon: iconBase + "info-i_maps.png"
        });

        my_marker.addListener("click", function () {
            messagewindow.setPosition({lat: user_lat, lng: user_lng});
            messagewindow.open(map);
        });*/

        messagewindow.setPosition({lat: user_lat, lng: user_lng});
        messagewindow.open(map);
        message_info.show();
        map.setCenter({lat: user_lat, lng: user_lng});

    }, function() {
        handleLocationError();
    });
} else {
    // Browser doesn't support Geolocation
    handleLocationError();
}

getDonors();

function handleLocationError() {
    alert("We were unable to detect your location");
}

// Refresh marker positions on map every 10 seconds
setInterval(function () {
    //getDonors();
}, 10000);

// Sets the map on all markers in the array.
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setMapOnAll(null);
}

function saveData() {
    var first_name = first_name_input.val();
    var last_name = last_name_input.val();
    var blood_type = blood_type_input.val();
    var latlng = marker.getPosition();
    var lat = latlng.lat();
    var lng = latlng.lng();

    var payload = {
        first_name: first_name,
        last_name: last_name,
        blood_type: blood_type,
        lat: lat,
        lng: lng,
        _token: _token
    };

    $.ajax({
        method: "POST",
        url: webRoot + "/save-data",
        data: JSON.stringify(payload),
        dataType: "json",
        contentType: "application/json"
    }).done(function () {

        // Close the donor form
        first_name_input.val("");
        last_name_input.val("");
        blood_type_input.val("");
        formWindow.close();

        // Populate the Donor Info Div
        donor_name.find("strong").html(first_name + ' ' + last_name);
        donor_type.find("strong").html(blood_type);
        // Open the donor info div for this marker
        donor_info.show();
        donorwindow.open(map, marker);

        // Add click listener for this marker
        marker.addListener('click', function () {
            donor_name.find("strong").html(first_name + ' ' + last_name);
            donor_type.find("strong").html(blood_type);
            donor_info.show();
            donorwindow.open(map, marker);
        });

    }).error(function (result) {
        var response_text = JSON.parse(result.responseText);

        console.log(response_text);
        console.log("Error");
        alert(response_text.message);

    });
}

// Function to get all donors from database using AJAX
function getDonors() {
    $.ajax({
        method: "GET",
        url: webRoot + "/get-donors/"+ ((user_lat !== null) ? user_lat : "") +"/"+ ((user_lng !== null) ? user_lng : "")
    }).done(function (result) {
        // Request is successful
        var donors = result.donors;

        // Remove all markers
        clearMarkers();

        // Loop through all markers from request and replace markers on map
        $.each(donors, function (key, value) {
            var new_marker = new google.maps.Marker({
                position: {lat: parseFloat(value.latitude), lng: parseFloat(value.longitude)},
                map: map
            });

            new_marker.addListener('click', function () {
                donor_name.find("strong").html(value.first_name + ' ' + value.last_name);
                donor_type.find("strong").html(value.blood_type);
                donor_info.show();
                donorwindow.open(map, new_marker);
            });

            markers.push(new_marker);
        });

    }).error(function (result) {
        var response_text = JSON.parse(result.responseText);

        console.log(response_text);
        console.log("Error");

    });
}