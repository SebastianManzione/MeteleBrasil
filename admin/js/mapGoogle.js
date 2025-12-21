/**

 * @license

 * Copyright 2019 Google LLC. All Rights Reserved.

 * SPDX-License-Identifier: Apache-2.0

 */

// @ts-nocheck TODO remove when fixed

let map;

let marker;

let geocoder;

let responseDiv;

let response;



function initMap() {

  map = new google.maps.Map(document.getElementById("map"), {

    zoom: 8,

    center: {

      lng: -51.92528,

      lat: -14.235004

    },

    mapTypeControl: false,

  });

  geocoder = new google.maps.Geocoder();



  const inputText = document.createElement("input");



  inputText.type = "text";

  inputText.placeholder = "";



  const submitButton = document.createElement("input");



  submitButton.type = "button";

  submitButton.value = "Procurar";

  submitButton.classList.add("button", "button-primary");



  const clearButton = document.createElement("input");



  clearButton.type = "button";

  clearButton.value = "apagar";

  clearButton.classList.add("button", "button-secondary");

  response = document.createElement("pre");

  response.id = "response";

  response.innerText = "";

  responseDiv = document.createElement("div");

  responseDiv.id = "response-container";

  responseDiv.appendChild(response);



  const instructionsElement = document.createElement("p");





  map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputText);

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(submitButton);

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(clearButton);

  map.controls[google.maps.ControlPosition.LEFT_TOP].push(instructionsElement);

  map.controls[google.maps.ControlPosition.LEFT_TOP].push(responseDiv);

  marker = new google.maps.Marker({

    map,

  });

  map.addListener("click", (e) => {

    geocode({

      location: e.latLng

    });

  });

  submitButton.addEventListener("click", () =>

    geocode({

      address: inputText.value

    })

  );

  clearButton.addEventListener("click", () => {

    clear();

  });

  clear();

}



function clear() {

  marker.setMap(null);

  responseDiv.style.display = "none";

}



function geocode(request) {

  clear();

  geocoder

    .geocode(request)

    .then((result) => {

      const {

        results

      } = result;



      map.setCenter(results[0].geometry.location);

      marker.setPosition(results[0].geometry.location);

      marker.setMap(map);

     // responseDiv.style.display = "block";

      //response.innerText = JSON.stringify(result, null, 2);

 

             $('#txtDireccion').attr('value', results[0].formatted_address);

                                         $('#txtDireccion2').attr('value', results[0].formatted_address);

                                      

                                        $('#txtLatitud').attr('value', results[0].geometry.location.lat());

                                        $('#txtLongitud').attr('value', results[0].geometry.location.lng());

                                           localizacion[0] = $('#idSrv').val();

                                        localizacion[1] = results[0].formatted_address;

                                        localizacion[2] = results[0].geometry.location.lat();

                                        localizacion[3] =  results[0].geometry.location.lng();

      return results;

    })

    .catch((e) => {

     

    });

}



window.initMap = initMap;

