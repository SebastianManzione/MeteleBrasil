              var localizacion = Array();
                                    var map, searchManager;

                                    function GetMap() {
                                        map = new Microsoft.Maps.Map('#myMap', {});
                                        Microsoft.Maps.loadModule(['Microsoft.Maps.AutoSuggest',
                                            'Microsoft.Maps.Search'
                                        ], function() {
                                            var manager = new Microsoft.Maps.AutosuggestManager({
                                                map: map
                                            });
                                            manager.attachAutosuggest('#searchBox', '#searchBoxContainer',
                                                suggestionSelected);
                                            searchManager = new Microsoft.Maps.Search.SearchManager(map);
                                        });
                                    }

                                    function suggestionSelected(result) {
                                        //Remove previously results from the map.
                                        map.entities.clear();
                                        //Show the suggestion as a pushpin and center map over it.
                                        var pin = new Microsoft.Maps.Pushpin(result.location);

                                        var latitud = result.location["latitude"];
                                        var longitud = result.location["longitude"];
                                        $('#txtDireccion').attr('value', result.address['formattedAddress']);
                                         $('#txtDireccion2').attr('value', result.address['formattedAddress']);
                                      
                                        $('#txtLatitud').attr('value', result.location["latitude"]);
                                        $('#txtLongitud').attr('value', result.location["longitude"]);
                                        // $('#direccion').attr('value', result.address['formattedAddress']);
                                        // $('#latitud').attr('value', result.location["latitude"]);
                                        // $('#longitud').attr('value', result.location["longitude"]);

                                        localizacion[0] = $('#idSrv').val();
                                        localizacion[1] = result.address['formattedAddress'];
                                        localizacion[2] = result.location['latitude'];
                                        localizacion[3] = result.location['longitude'];

                                        /*  alert("formattedAddress:"+result.address['formattedAddress']+" adminDistrict:"+result.address['adminDistrict']+
                                        +" countryRegion:"+result.address['countryRegion']+
                                        " cp:"+result.address['postalCode']+" lat:"+latitud+" lon:"+longitud);
                                        */
                                        map.entities.push(pin);
                                        map.setView({
                                            bounds: result.bestView
                                        });
                                    }

                                    function geocode() {
                                        //Remove previously results from the map.
                                        map.entities.clear();
                                        //Get the users query and geocode it.
                                        var query = document.getElementById('searchBox').value;
                                        var searchRequest = {

                                            where: query,
                                            callback: function(r) {
                                                if (r && r.results && r.results.length > 0) {
                                                    var pin, pins = [],
                                                        locs = [],
                                                        output = 'Resultados:<br/>';
                                                    //Add a pushpin for each result to the map and create a list to display.
                                                    for (var i = 0; i < r.results.length; i++) {
                                                        //Create a pushpin for each result.
                                                        pin = new Microsoft.Maps.Pushpin(r.results[i]
                                                            .location, {
                                                                text: i + ''
                                                            });
                                                        pins.push(pin);
                                                        locs.push(r.results[i].location);
                                                        output += i + ') ' + r.results[i].name + '<br/>';
                                                    }
                                                    //Add the pins to the map
                                                    map.entities.push(pins);
                                                    //Display list of results
                                                    document.getElementById('output').innerHTML = output;
                                                    //Determine a bounding box to best view the results.
                                                    var bounds;

                                                    if (r.results.length == 1) {
                                                        bounds = r.results[0].bestView;
                                                    } else {
                                                        //Use the locations from the results to calculate a bounding box.
                                                        bounds = Microsoft.Maps.LocationRect.fromLocations(
                                                            locs);
                                                    }
                                                    map.setView({
                                                        bounds: bounds,
                                                        padding: 30
                                                    });
                                                }
                                            },
                                            errorCallback: function(e) {
                                                document.getElementById('output').innerHTML = "Sin resultados.";
                                            }
                                        };
                                        //Make the geocode request.
                                        searchManager.geocode(searchRequest);
                                    }