@extends('menu')

@section('css')
    <style type="text/css" class="init">

        body {
            font-family: arial;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        table , tr , td {
            font-size: small;
        }
    </style>
@endsection

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <div class="col-sm-6 col-md-9">
                <h4><i class="material-icons">find_in_page</i> GRÁFICO DE SUBASSUNTOS DA DEMANDA</h4>
            </div>
            {{--<div class="col-sm-6 col-md-3">
                <a href="{{ route('seracademico.ouvidoria.graficos.subassunto') }}" target="_blank" class="btn-sm btn-primary pull-right">Imprimir</a>
            </div>--}}
        </div>

        <div class="ibox-content">
            <div id="container" style=" margin: 0 auto"></div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('/js/plugins/highcharts.js')  }}"></script>
    <script src="{{ asset('/js/plugins/exporting.js')  }}"></script>
    <script type="text/javascript">

        $(document).ready(function(){

            $.ajax({
                url: '{{route('seracademico.ouvidoria.graficos.subassuntoAjax')}}',
                type: 'POST',
                dataType: 'JSON',
                success: function (json) {

                    console.log(json[0]);

                    $(function () {
                        Highcharts.chart('container', {
                            chart: {
                                type: 'bar'
                            },
                            title: {
                                text: 'Quantidade de demandas por subassunto'
                            },
                            xAxis: {
                                categories: json[0],
                                title: {
                                    text: null
                                }
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Subassunto',
                                    align: 'high'
                                },
                                labels: {
                                    overflow: 'justify'
                                }
                            },
                            tooltip: {
                                valueSuffix: ''
                            },
                            plotOptions: {
                                bar: {
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            credits: {
                                enabled: false
                            },
                            series: [{
                                name: 'Quantidade',
                                data: json[1]
                            }]
                        });
                    });

                }
            });
        });

    </script>
@stop