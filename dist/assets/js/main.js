//ymaps.ready(init);
var myMap,
    myPlacemark;

var cityes = ['Москва','Волгоград', 'Санкт-Петербург'];
var cityNumber = ['0', '1', '2'];

var targetCity = 0;

var moscow = {
    idMap: 1,
    cordinates: {
        x: '55.76',
        y: '37.64'
    },
    zoom: 10,
    markets: [
        {
            posX: "55.79",
            posY: "37.64",
            marketName: "Самострой",
            marketText: "<p style='color: red'>тел. 8 800 000 00 00<br>тел. 8 800 000 00 00</p>",
            marketLink: "<a href='//ya.ru' target='_blank'>test</a>"
        },
        {
            posX: "55.76",
            posY: "37.64",
            marketName: "Метизы",
            marketText: "тел. 8 800 000 00 00",
            marketLink: ""
        }
    ]
}

var volgograd = {
    idMap: 2,
    cordinates: {
        x: '48.72355642',
        y: '44.47880116'
    },
    zoom: 10,
    markets: [
        {
            posX: "48.70439882",
            posY: "44.50945031",
            marketName: "Домовенок",
            marketText: "<p style='color: red'>тел. 8 800 000 00 00<br>тел. 8 800 000 00 00</p>",
            marketLink: "<a href='//ya.ru' target='_blank'>test</a>"
        },
        {
            posX: "48.71211158",
            posY: "44.52677651",
            marketName: "ДонСтрой",
            marketText: "тел. 8 800 000 00 00",
            marketLink: ""
        }
    ]
}

var spb = {
    idMap: 3,
    cordinates: {
        x: '59.93329178',
        y: '30.36598448'
    },
    zoom: 10,
    markets: [
        {
            posX: "59.93329178",
            posY: "30.36598448",
            marketName: "Домовенок 2",
            marketText: "<p style='color: red'>тел. 8 800 000 00 00<br>тел. 8 800 000 00 00</p>",
            marketLink: "<a href='//ya.ru' target='_blank'>test</a>"
        }
    ]
}

var cities = [moscow, volgograd, spb]

var cityJSON = JSON.stringify( cities[targetCity] );
var currentCity = JSON.parse(cityJSON);

function parseCityJSON() {
    cityJSON = JSON.stringify( cities[targetCity] );
    currentCity = JSON.parse(cityJSON);
}
//console.log(currentCity);

//var cart = JSON.parse ( jsonString );
var cityPoint;

function init(){

    myMap = new ymaps.Map("ymwap__map", {
        center: [currentCity.cordinates.x , currentCity.cordinates.y],
        zoom: currentCity.zoom
    });
    //console.log(currentCity.markets.length);
    // myPlacemark = new ymaps.Placemark([currentCity.markets[0].posX, currentCity.markets[0].posY], { hintContent: currentCity.markets[0].hintContent , balloonContent: currentCity.markets[0].balloonContent });
    // myMap.geoObjects.add(myPlacemark);
    //var myCollection = new ymaps.GeoObjectCollection();
    var myGeoObjects = [];

    for (var i = 0; i < currentCity.markets.length; i++) {
        myGeoObjects[i] = new ymaps.GeoObject({
            geometry: {
              type: "Point",
              coordinates: [currentCity.markets[i].posX, currentCity.markets[i].posY]
          },
          properties: {
              clusterCaption: currentCity.markets[i].hintContent,
              balloonContentHeader: currentCity.markets[i].marketName,
              balloonContentBody: currentCity.markets[i].marketText,
              balloonContentFooter: currentCity.markets[i].marketLink
          }
        });
    }

    // for (var i = 0; i < currentCity.markets.length; i++) {
    //     myCollection.add(new ymaps.Placemark([currentCity.markets[i].posX, currentCity.markets[i].posY], { hintContent: currentCity.markets[i].hintContent , balloonContent: currentCity.markets[i].balloonContent }));
    // }

    var myClusterer = new ymaps.Clusterer();
    myClusterer.add(myGeoObjects);
    myMap.geoObjects.add(myClusterer);
    // Устанавливаем карте центр и масштаб так, чтобы охватить коллекцию целиком.
    // // myMap.setBounds(myClusterer.getBounds());

    // Данные о местоположении, определённом по IP
    // var geolocation = ymaps.geolocation;
    // console.log(geolocation.city);
    // console.log(geolocation.latitude+' '+geolocation.longitude);
    // console.log(geolocation.region);
    // console.log(geolocation.country);


    // ymaps.geolocation.get({
    //     provider: 'yandex'
    // }).then(function (result) {
    //     visitorLocationObject = result.geoObjects.get(0).properties.get('metaDataProperty');
    //     cityPoint = visitorLocationObject.GeocoderMetaData.AddressDetails.Country.AddressLine;
    //     console.log(cityPoint);
    // });

    // for (var i = 0; i < currentCity.markets.length; i++) {
    //     myPlacemark = new ymaps.Placemark([currentCity.markets[i].posX, currentCity.markets[i].posY], { hintContent: currentCity.markets[i].hintContent , balloonContent: currentCity.markets[i].balloonContent });
    //     myMap.geoObjects.add(myPlacemark);
    // }
}

$(document).ready(function() {

    ymaps.ready(FirstInit);
    function FirstInit(){
        // myMap = new ymaps.Map("ymwap__map", {
        //     center: [currentCity.cordinates.x , currentCity.cordinates.y],
        //     zoom: currentCity.zoom
        // });
        ymaps.geolocation.get({
            provider: 'yandex'
        }).then(function (result) {
           visitorLocationObject = result.geoObjects.get(0).properties.get('metaDataProperty');
           cityPoint = visitorLocationObject.GeocoderMetaData.AddressDetails.Country.AddressLine;
           console.log(cityPoint);
           //
           for (var i = 0; i < cityes.length; i++) {
               if (cityes[i] == cityPoint) {
                   //alert("Есть совпадение")
                   targetCity = i;
                   //alert(targetCity)
                   $('.ymwap__popup__title span').html(cityes[targetCity]);
                   myMap.destroy();
                   break;
               } else {
                   //alert("нету");
                   targetCity = 0;
                   $('.ymwap__popup__title span').html(cityes[targetCity]);
               }
           }
        });
    }
});


$(document).ready(function() {

    var initMap = function(){
        $('.ymwap__map-question').hide();
        $('.ymwap__map-title').addClass('init');
        parseCityJSON();
        init();
    }

    $(document).on('click', '#ymwap__yes', function(event) {
        event.preventDefault();
        //targetCity = 0;
        initMap();
    });
    $(document).on('click', '#ymwap__no', function(event) {
        event.preventDefault();
        $('.ymwap__popup-1').removeClass('visible');
        $('.ymwap__popup-2').addClass('visible');
        //
        $('.ymwap__cities-list').html('');
        for (var i = 0; i < cityes.length; i++) {
            $('.ymwap__cities-list').append('<div class="ymwap__cities-list__item" data-number="' + cityNumber[i] + '">' + cityes[i] + '</div>');
        }
    });
    $(document).on('click', '.ymwap__cities-list__item', function(event) {
        event.preventDefault();
        $('.ymwap__cities-list__item').removeClass('selected');
        $(this).addClass('selected');
    });
    $(document).on('click', '#ymwap__choise-city', function(event) {
        event.preventDefault();
        if ($('.ymwap__cities-list__item').hasClass('selected')) {
            targetCity = $('.ymwap__cities-list__item.selected').data('number')
            //console.log(targetCity);
            initMap();
        } else {
            alert("Выбири город!");
        }
    });


});
