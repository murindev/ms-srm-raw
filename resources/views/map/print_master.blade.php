{{--@dump($helpers,$date,$master)--}}
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
        <td colspan="6">{{$date}}: {{$master->name->name}}</td>
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
    @foreach($master->volumes as $i)
        <tr>
            <td>

                @foreach($i->lid as $lid)
                    {{--№договора--}}
                    @if($lid->contract) {{$lid->contract}}  @endif &nbsp;&nbsp;&nbsp;
                    {{--Вид оплаты--}}
                    @if($lid->customer_payment)
                        <br/>  Вид оплаты: <b> @if($lid->customer_payment) Безналичный расчет @else Наличный расчет  @endif</b>
                    @endif
                @endforeach
            </td>
            <td>
                {{--Время--}}
                с @if($i->start) {{$i->start}}  @endif
                по @if($i->end) {{$i->end}}  @endif
            </td>
            <td>
                {{--Наименование организации--}}
                @isset($i->customer->organization) {{$i->customer->organization}}  @endisset <br/>
                @isset($i->customer->phone) {{$i->customer->phone}}  @endisset <br/>
                {{--Имя контакта--}}
                @isset($i->customer->name) {{$i->customer->name}}  @endisset <br/>
                @foreach($i->actsArray as $act)
                    <br/> <b>Акт №:</b> {{$act->act_nr}} <br/>
                    Адрес
                    @if($act->address)
                        {{$act->address}} &nbsp;&nbsp;&nbsp; <br/>
                    @endif
                    Телефон
                    @if($act->phone)
                        {{$act->phone}} &nbsp;&nbsp;&nbsp; <br/>
                    @endif
                    @if($act->phone_ext)
                        {{$act->phone_ext}} &nbsp;&nbsp;&nbsp; <br/>
                    @endif
                @endforeach
            </td>
            <td>
                @php
                $total = 0;
                @endphp
                @foreach($i->volume as $volume)
                    {{--Перечень работ--}}
                    @if($volume->pest) <b>{{$helpers['pests'][$volume->pest]['name']}} : </b>@endif
                    {{--Метод--}}
                    @if($volume->method)
                        @foreach($volume->method as $method)

                         @if(!empty($helpers['methods'][$method]['name'])) {{$helpers['methods'][$method]['name']}} / @endif
                        @endforeach
                        <br/>
                    @endif
                    {{--Площадь--}}
                     @if($volume->square)  <b>{{$volume->square}}</b> @endif
                     @if($volume->entity) ( {{$helpers['square'][$volume->entity]['name']}} )  <br/> @endif
                    {{--Цена--}}
                     @if($volume->price_fact)
                        <b>Стоимость:</b> {{$volume->price_fact}} <br/>
                         @php
                             $total = $total + floatval($volume->price_fact)
                         @endphp
                    @endif

                @endforeach
            </td>
            <td> {{$total}} </td>
            <td>
                @foreach($i->lid as $lid)
                    {{--№договора--}}
                    @if($lid->comment) {{$lid->comment}}  @endif
                @endforeach
            </td>
        </tr>
    @endforeach

    </tbody>

</table>
