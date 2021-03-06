<!DOCTYPE html>

<html>

<head>
    <title>Получение сегментов маршрута</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--
        Укажите свой API-ключ. Тестовый ключ НЕ БУДЕТ работать на других сайтах.
        Получить ключ можно в Кабинете разработчика: https://developer.tech.yandex.ru/keys/
    -->
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script src="https://yandex.st/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>
    <script src="router.js" type="text/javascript"></script>
    <style>
        body, html {
            padding: 0;
            margin: 0;
            width: 100%;
            height: 100%;
            font-family: Arial;
            font-size: 14px;
        }
        #list {
            padding: 10px;
        }
        #map {
            width: 95%; height: 200px;
        }
    </style>
</head>

<body>
<div id="map"></div>
<div id="list"></div>
</body>

</html>

<script>
    ymaps.ready(init);

    function init() {
        var myMap = new ymaps.Map("map", {
            center: [55.745508, 37.435225],
            zoom: 13
        }, {
            searchControlProvider: 'yandex#search'
        });

        // Добавим на карту схему проезда
        // от улицы Крылатские холмы до станции метро "Кунцевская"
        // через станцию "Молодежная" и затем до станции "Пионерская".
        // Точки маршрута можно задавать 3 способами:
        // как строка, как объект или как массив геокоординат.
        ymaps.route([
            'Москва, улица Крылатские холмы',
            {
                point: 'Москва, метро Молодежная',
                // метро "Молодежная" - транзитная точка
                // (проезжать через эту точку, но не останавливаться в ней).
                type: 'viaPoint'
            },
            [55.731272, 37.447198], // метро "Кунцевская".
            'Москва, метро Пионерская'
        ]).then(function (route) {
            myMap.geoObjects.add(route);
            // Зададим содержание иконок начальной и конечной точкам маршрута.
            // С помощью метода getWayPoints() получаем массив точек маршрута.
            // Массив транзитных точек маршрута можно получить с помощью метода getViaPoints.
            var points = route.getWayPoints(),
                lastPoint = points.getLength() - 1;
            // Задаем стиль метки - иконки будут красного цвета, и
            // их изображения будут растягиваться под контент.
            points.options.set('preset', 'islands#redStretchyIcon');
            // Задаем контент меток в начальной и конечной точках.
            points.get(0).properties.set('iconContent', 'Точка отправления');
            points.get(lastPoint).properties.set('iconContent', 'Точка прибытия');

            // Проанализируем маршрут по сегментам.
            // Сегмент - участок маршрута, который нужно проехать до следующего
            // изменения направления движения.
            // Для того, чтобы получить сегменты маршрута, сначала необходимо получить
            // отдельно каждый путь маршрута.
            // Весь маршрут делится на два пути:
            // 1) от улицы Крылатские холмы до станции "Кунцевская";
            // 2) от станции "Кунцевская" до "Пионерская".

            var moveList = 'Трогаемся,</br>',
                way,
                segments;
            // Получаем массив путей.
            for (var i = 0; i < route.getPaths().getLength(); i++) {
                way = route.getPaths().get(i);
                segments = way.getSegments();
                for (var j = 0; j < segments.length; j++) {
                    var street = segments[j].getStreet();
                    moveList += ('Едем ' + segments[j].getHumanAction() + (street ? ' на ' + street : '') + ', проезжаем ' + segments[j].getLength() + ' м.,');
                    moveList += '</br>'
                }
            }
            moveList += 'Останавливаемся.';
            // Выводим маршрутный лист.
            $('#list').append(moveList);
        }, function (error) {
            alert('Возникла ошибка: ' + error.message);
        });
    }
</script>
