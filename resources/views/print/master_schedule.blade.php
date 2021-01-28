<style>
    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .table th,
    .table td {
        padding: 5mm;
        vertical-align: top;
        border-top: 1px solid;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #eceeef;
    }

    .table tbody + tbody {
        border-top: 2px solid #eceeef;
    }

    .table .table {
        background-color: #fff;
    }


    .table-bordered th,
    .table-bordered td {
        border: 1px solid #eceeef;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-active,
    .table-active > th,
    .table-active > td {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-hover .table-active:hover > td,
    .table-hover .table-active:hover > th {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .table-success,
    .table-success > th,
    .table-success > td {
        background-color: #dff0d8;
    }

    .table-hover .table-success:hover {
        background-color: #d0e9c6;
    }

    .table-hover .table-success:hover > td,
    .table-hover .table-success:hover > th {
        background-color: #d0e9c6;
    }

    .table-info,
    .table-info > th,
    .table-info > td {
        background-color: #d9edf7;
    }

    .table-hover .table-info:hover {
        background-color: #c4e3f3;
    }

    .table-hover .table-info:hover > td,
    .table-hover .table-info:hover > th {
        background-color: #c4e3f3;
    }

    .table-warning,
    .table-warning > th,
    .table-warning > td {
        background-color: #fcf8e3;
    }

    .table-hover .table-warning:hover {
        background-color: #faf2cc;
    }

    .table-hover .table-warning:hover > td,
    .table-hover .table-warning:hover > th {
        background-color: #faf2cc;
    }

    .table-danger,
    .table-danger > th,
    .table-danger > td {
        background-color: #f2dede;
    }

    .table-hover .table-danger:hover {
        background-color: #ebcccc;
    }

    .table-hover .table-danger:hover > td,
    .table-hover .table-danger:hover > th {
        background-color: #ebcccc;
    }

    .thead-inverse th {
        color: #fff;
        background-color: #292b2c;
    }

    .thead-default th {
        color: #464a4c;
        background-color: #eceeef;
    }

    .table-inverse {
        color: #fff;
        background-color: #292b2c;
    }

    .table-inverse th,
    .table-inverse td,
    .table-inverse thead th {
        border-color: #fff;
    }

    .table-inverse.table-bordered {
        border: 0;
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .table-responsive.table-bordered {
        border: 0;
    }
</style>

<table class="table">

    <thead>
    <tr>
        <td colspan="6">
            <span style="float: right">{{$date}}: {{$h['users'][$master]->name}}</span>

        </td>
    </tr>
    <tr>
        <th>#</th>
        <th>Время</th>
        <th>Заказчик</th>
        <th>Обьемы работ</th>
        <th>Оплата</th>
        <th>Примечание</th>


    </tr>
    </thead>

    <tbody>
    @foreach($list as $item)
        <tr>
            <td>
                №договора:
                <b>{{$item['lid']->contract}}</b> <br/>

                Вид оплаты:
                <b> @if($item['lid']->customer_payment) Безналичный расчет @else Наличный расчет  @endif</b>

            </td>
            <td>
                Время
                Начало обработки <br/>
                <b>{{$item['implement']->start_date}}</b> <br/>
                Окончание обработки <br/>
                <b>{{$item['implement']->end_date}}</b>
            </td>
            <td>
                Наименование организации :
                <b>@isset($item['customer']->organization) {{$item['customer']->organization}} @endisset</b> <br/>
                <b>@isset($item['customer']->phone) {{$item['customer']->phone}} @endisset</b> <br/>
                Имя контакта :
                <b>@isset($item['customer']->name) {{$item['customer']->name}} @endisset</b> <br/>
                <br/> Акт №: <b>{{$item['act']->act_nr}}</b> <br/>
                Адрес:
                @isset($item['act']->address)<b> {{$item['act']->address}}</b> @endisset

                @isset($item['act']->phone) Телефон: <b>{{$item['act']->phone}}</b>  @endisset
               @isset($item['act']->phone_ext)  <b>{{$item['act']->phone_ext}}</b> @endisset
            </td>
            <td>
                @php $total = 0; @endphp

                @foreach($item['act']->volumes as $volume)
                    Перечень работ
                @isset($volume->pest) <b>{{$h['pests'][$volume->pest]['name']}}</b> @endisset
                    <br/>
                    Метод
                    @isset($volume->method)
                        @foreach($volume->method as $method)
                            @isset($h['methods'][$method]['name'])
                                <b> / {{$h['methods'][$method]['name']}}</b>
                            @endisset
                        @endforeach
                        <br/>
                    @endisset
                    Площадь
                    @if($volume->square)  <b>{{$volume->square}}</b> @endif
                    @if($volume->entity) ( {{$h['square'][$volume->entity]['name']}} )  <br/> @endif

                    @if($volume->price_fact)
                        Стоимость: <b>{{$volume->price_fact}}</b> <br/>
                        @php $total = $total + floatval($volume->price_fact) @endphp
                    @endif
                    <br/>
                    <br/>
                @endforeach
            </td>
            <td>
                {{$total}}
            </td>
            <td>

                @isset($item['lid']->comment)Общее: <b>{{$item['lid']->comment}}</b> @endisset

                @isset($item['act']->comment)К акту: <b>{{$item['act']->comment}}</b> @endisset
            </td>
        </tr>
    @endforeach

    </tbody>

</table>
