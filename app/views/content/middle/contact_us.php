<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"
                    type="text/javascript"></script>

<script type="text/javascript">
    // Как только будет загружен API и готов DOM, выполняем инициализацию
    ymaps.ready(init);

    function init () {

            var myMap = new ymaps.Map("map", {
                    center: [55.76, 37.64],
                    zoom: 10
                }),


                // Создаем метку. При создании метки указываем ее свойство -  текст для отображения в иконке.
                myPlacemark = new ymaps.Placemark([55.665152,37.471929], {
                    // Свойства
                    // Текст метки
                    iconContent: 'Attorney of Business'
                }, {
                    // Опции
                    // Иконка метки будет растягиваться под ее контент
                    preset: 'twirl#blueStretchyIcon'
                });

            myMap.controls
            // Кнопка изменения масштаба
            .add('zoomControl')
            // Список типов карты
            .add('typeSelector')
            // Кнопка изменения масштаба - компактный вариант
            // Расположим её справа
            .add('smallZoomControl', { right: 5, top: 75 })
            // Стандартный набор кнопок
            .add('mapTools');

            // Добавляем метки на карту
            myMap.geoObjects
                .add(myPlacemark);
    }
</script>

<div class="content" id="contact">
    <h1>Связь с нами<span></span></h1>
    <p>119602, г. Москва, улица Покрышкина, домостроение 8<span class="metro">Юго-Западная</span></p>
    <p>+7 (495) 698-61-80</p>
    <div class="map">
        <div class="l-t"></div>
        <div class="r-t"></div>
        <div class="r-b"></div>
        <div class="l-b"></div>
        <div id="map" style="width:950px; height:500px"></div>


    </div>
    <? $this->load->view('content/middle_slave/contact_us_form') ?>
</div>