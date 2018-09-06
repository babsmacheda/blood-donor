<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1">
    <meta name="description" content="A web platform that leverages on the map capabilities of Google Maps or ArcGIS to aid people find blood donors closest to them using a map interface where clickable markers, denoting the location of available donors, will be visible">

    <title>{{ ucwords(config("app.name")) }} | Welcome</title>

    <link rel="stylesheet" href="{{ asset("css/custom.css") }}" />

</head>
<body>

<div>
    <h1>{{ ucwords(config("app.name")) }}</h1>

    <div id="map"></div>

    <div id="form">
        <table>
            <tr>
                <td>
                    First Name:
                </td>
                <td>
                    <input type="text" id="first_name">
                </td>
            </tr>
            <tr>
                <td>
                    Last Name:
                </td>
                <td>
                    <input type="text" id="last_name">
                </td>
            </tr>
            <tr>
                <td>
                    Blood type:
                </td>
                <td>
                    <select id="blood_type"> +
                        <option value="">Please select</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td>
                    <input type='button' value='Save' onclick='saveData()'/>
                </td>
            </tr>
        </table>
    </div>

    <div id="info">
        <div id="name">
            Name: <strong></strong>
        </div>
        <div id="donor_type">
            Blood Type: <strong></strong>
        </div>
    </div>

    <div id="message">You are here</div>
</div>

<script type="text/javascript" src="{{ asset("js/jquery-2.1.4.min.js") }}"></script>
<script>
    const webRoot = "{{ url("/") }}";
    const _token = "{{ csrf_token()  }}";
</script>
<script type="text/javascript" src="{{ asset("js/custom.js") }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABCMLE0VIX7219B-QAA4C22ObsOjEHEmk&callback=initMap" async defer></script>
</body>
</html>
