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

            <div class="row">
                {!! Form::open(['method' => "POST"]) !!}
                <div class="col-md-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('secretaria', 'Secretaria *') !!}
                            {!! Form::select('secretaria', (["" => "Selecione"] + $loadFields['ouvidoria\secretaria']->toArray()), Session::getOldInput('secretaria'), array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="assunto">Assuntos</label>
                            <select name="assunto" class="form-control" id="assunto">
                                {{--@foreach($assuntos as $assunto)
                                    <option value="{{$assunto->id}}">{{$assunto->nome}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php $data = new \DateTime('now') ?>
                            {!! Form::label('data_inicio', 'Início') !!}
                            {!! Form::text('data_inicio', null , array('class' => 'form-control date datepicker')) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('data_fim', 'Fim') !!}
                            {!! Form::text('data_fim', null , array('class' => 'form-control date datepicker')) !!}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" style="margin-top: 22px" id="search" class="btn-primary btn input-sm">Consultar</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div><br />
            <div class="row">
                <div id="container" style=" margin: 0 auto"></div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ asset('/js/plugins/highcharts.js')  }}"></script>
    <script src="{{ asset('/js/plugins/exporting.js')  }}"></script>
    <script type="text/javascript">

        $(document).ready(function(){

            /*$.ajax({
                url: '{{route('seracademico.ouvidoria.graficos.subassuntoAjax')}}',
                type: 'POST',
                dataType: 'JSON',
                success: function (json) {
                        grafico(json);
                }
            });*/
        });

        $(document).on('click', '#search', function(){

            var data_inicio = $('input[name=data_inicio]').val();
            var data_fim    = $('input[name=data_fim]').val();
            var assunto    = $('select[name=assunto] option:selected').val();

            var dados = {
                'data_inicio': data_inicio,
                'data_fim': data_fim,
                'assunto' : assunto
            };

            $.ajax({
                url: '{{route('seracademico.ouvidoria.graficos.subassuntoAjax')}}',
                type: 'POST',
                dataType: 'JSON',
                data: dados,
                success: function (json) {
                    grafico(json)
                }
            });

        });

        function grafico (json) {
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

        //Carregando os assuntos
        $(document).on('change', "#secretaria", function () {
            //Removendo as assuntos
            $('#assunto option').remove();

            //Recuperando a secretaria
            var secretaria = $(this).val();

            if (secretaria !== "") {
                var dados = {
                    'table' : 'ouv_assunto',
                    'field_search' : 'area_id',
                    'value_search': secretaria,
                };

                jQuery.ajax({
                    type: 'POST',
                    url: '{{ route('seracademico.util.search')  }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{  csrf_token() }}'
                    },
                    data: dados,
                    datatype: 'json'
                }).done(function (json) {
                    var option = "";

                    option += '<option value="">Selecione um assunto</option>';
                    for (var i = 0; i < json.length; i++) {
                        option += '<option value="' + json[i]['id'] + '">' + json[i]['nome'] + '</option>';
                    }

                    $('#assunto option').remove();
                    $('#assunto').append(option);
                });
            }
        });
    </script>
@stop