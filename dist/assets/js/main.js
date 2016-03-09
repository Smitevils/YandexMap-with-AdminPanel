$(document).ready(function() {

// обьевляем переменные
var myMap, // карта
    myPlacemark, // ярлык
    currentCity; // переменная для хранения
var cityPoint;

var cityes = ['Москва','Волгоград', 'Санкт-Петербург']; // массив городов
var cityNumber = ['0', '1', '2']; // массив с номерами городов

var targetCity = 0; // переменная выбранного города

// обекты городов //
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
// /обекты городов //

var cities = [moscow, volgograd, spb] // массив с обьектами городов

function parseCityJSON() {
    cityJSON = JSON.stringify( cities[targetCity] );//берем выбранный город и превращаем в строку JSON
    currentCity = JSON.parse(cityJSON); // возвращеем JSON в обьект
}
parseCityJSON();



function init(){ // функция инициализации карты
    myMap = new ymaps.Map("ymwap__map", {
        center: [currentCity.cordinates.x , currentCity.cordinates.y], // берем данные из
        zoom: currentCity.zoom
    });

    var myGeoObjects = []; // создаем переменную дя геообьектов

    for (var i = 0; i < currentCity.markets.length; i++) { //пробегаемся по массиву маркеров
        myGeoObjects[i] = new ymaps.GeoObject({ // добавляем в геобьекты маркеры
            geometry: {
              type: "Point",
              coordinates: [currentCity.markets[i].posX, currentCity.markets[i].posY] // берем данные из массива с порядковым номером
          },
          properties: {
              clusterCaption: currentCity.markets[i].hintContent, // берем данные из массива с порядковым номером
              balloonContentHeader: currentCity.markets[i].marketName, // берем данные из массива с порядковым номером
              balloonContentBody: currentCity.markets[i].marketText, // берем данные из массива с порядковым номером
              balloonContentFooter: currentCity.markets[i].marketLink // берем данные из массива с порядковым номером
          }
        });
    }

    var myClusterer = new ymaps.Clusterer(); // создаем кластер
    myClusterer.add(myGeoObjects);  // кладем в кластер
    myMap.geoObjects.add(myClusterer); // добавляем кластер в геообьекты карты
}




function provisionalInit(){ // проверяем куки или узнаем город
    var geoDataCookie; // куки выбранного города
    if ($.cookie('geoData') != undefined) {
        geoDataCookie =  $.cookie('geoData');
        targetCity = geoDataCookie;
        //$('.ymwap__popup__title span').html(cityes[targetCity]); // подставляем имя города
        //$('.ymwap__popup__title span').attr('data-target', targetCity);
        openMap();
    } else {
        ymaps.geolocation.get({
            provider: 'yandex'
        }).then(function (result) {
           visitorLocationObject = result.geoObjects.get(0).properties.get('metaDataProperty');
           cityPoint = visitorLocationObject.GeocoderMetaData.AddressDetails.Country.AddressLine;
           console.log(cityPoint);
           //
           for (var i = 0; i < cityes.length; i++) {
               if (cityes[i] == cityPoint) { // если имя города совпадает с одним из существующих
                   targetCity = i; // присвоить выбранный город
                   $('.ymwap__popup__title span').html(cityes[targetCity]); // подставляем имя города
                   myMap.destroy(); // уничтожаем ранне созданную предварительную карту
                   break;
               } else {
                   targetCity = 0;
                   $('.ymwap__popup__title span').html(cityes[targetCity]); // подставляем имя города
               }
           }
        });
    }
}
ymaps.ready(provisionalInit);

var openMap = function(){
    $('.ymwap__map-question').hide();
    $('.ymwap__map-title').addClass('init');
    parseCityJSON(); //создаем из строки обьект с данными города
    init(); // запускаем карту
}

$(document).on('click', '#ymwap__yes', function(event) {
    event.preventDefault();
    openMap();
    $.cookie('geoData', targetCity);
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
        openMap();
        $.cookie('geoData', targetCity);
    } else {
        alert("Выбири город!");
    }
});

});
