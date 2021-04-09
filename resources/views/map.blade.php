<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Google Maps Example</title>
    {!! $map['js'] !!}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <style>
        html {
            height: 100%
        }

        body {
            height: 100%;
            margin: 0px;
            padding: 0px
        }

        .map {
            width: 100%;
            height: 100%;
        }

        #wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }

        #over_map {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 99;
        }

        .disappear {
            display: none;
        }

        .sugestions {
            cursor: pointer;
        }

        .buttonDiv {
            position: fixed;
            bottom: 10px;
        }


    </style>
    <script>
        let marker1, marker2, flightPath
        window.onload = function() { // same as window.addEventListener('load', (event) => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
        };

        function showPosition(position) {
            setTimeout(() => {
                position = new google.maps.LatLng(
                    position.coords.latitude,
                    position.coords.longitude
                );
                map.setCenter(position);

                marker1 = new google.maps.Marker({
                    position,
                    map,
                });

            }, 3000)

        }

    </script>
</head>


<body>

    <div id="wrapper">
        <div id="" class="map">
            {!! $map['html'] !!}
        </div>

        <div id="over_map">

            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title text-center">Parcel Request</h5>
                    <p class="card-text">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="text" id="search" class="form-control" placeholder="Enter Pickoff Address">
                                <div id="locations" class="disappear mt-2 w-75"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" id="search2" class="form-control"
                                    placeholder="Enter the Dropoff address">
                                <div id="locations2" class="disappear mt-2 w-75"></div>
                            </div>
                        </div>
                    </form>
                    </p>
                    <a href="#" class="btn btn-success" id="direction">Get Direction </a>
                </div>
            </div>

            <div class="card buttonDiv w-100">
                <div class="card-body">

                    <p class="card-text">
                        <div > <span class="price">140,000</span> <span class="float-right kilo">3.4km / 25 min</span> </div>
                        <button class="btn btn-success w-100 ">Enter parcel Details</button>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>
    <script>
        document.getElementById("search").addEventListener("keyup", (e) => {
            search(e.target.value);
        });

        document.getElementById("search2").addEventListener("keyup", (e) => {
            search(e.target.value, 'locations2');
        });

        document.getElementById("search").oninput = () => {
            document.getElementById("locations").classList.remove("disappear");
        };

        document.getElementById("search2").oninput = () => {
            document.getElementById("locations2").classList.remove("disappear");
        };

        document.getElementById("search").onblur = () => {
            setTimeout(() => {
                document.getElementById("locations").classList.add("disappear");
            }, 500);
        };

        document.getElementById("search2").onblur = () => {
            setTimeout(() => {
                document.getElementById("locations2").classList.add("disappear");
            }, 500);
        };

        document.getElementById("direction").onclick = () => {
            if (!marker1 || !marker2) {
                return
            }

            console.log(marker1.position, marker2.position);

            let flightPlanCoordinates = new Array(marker1.position, marker2.position);

            flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

            flightPath.setMap(map);
        }

        function search(q, l = 'locations') {
            link = `https://eu1.locationiq.com/v1/search.php?key=a34812394d17ab&q=${q}&format=json`;
            if (!q.length) {
                document.getElementById(l).classList.add('disappear');
                return
            }
            fetch(link).then((res) => {
                res.json().then((data) => {
                    str = "";
                    if (data.error) {
                        return;
                    }
                    data.forEach((element) => {
                        str +=
                            `<li class="sugestions"  data-lng="${element.lon}" data-lat="${element.lat}"><span data-lng="${element.lon}" data-lat="${element.lat}">${element.display_name}</span></li>`;
                    });
                    document.getElementById(l).innerHTML = str;


                    document.querySelectorAll(".sugestions").forEach((sugestion) => {
                        sugestion.onclick = (e) => {
                            if (l == 'locations') {
                                document.querySelector('#search').value = e.target.innerHTML
                            } else {
                                document.querySelector('#search2').value = e.target
                                    .innerHTML
                            }

                            let position = new google.maps.LatLng(
                                e.target.getAttribute("data-lat"),
                                e.target.getAttribute("data-lng")
                            );

                            if (flightPath) {
                                flightPath.setMap(null)
                            }

                            if (l == 'locations') {
                                marker1.setMap(null)
                                marker1 = new google.maps.Marker({
                                    position,
                                    map,
                                });
                            } else {
                                if (marker2) {
                                    marker2.setMap(null)
                                }
                                marker2 = new google.maps.Marker({
                                    position,
                                    map,
                                });
                            }
                            if (l == 'locations')
                                map.setCenter(position);
                        };
                    });
                });
            });

        }

    </script>
</body>

</html>
