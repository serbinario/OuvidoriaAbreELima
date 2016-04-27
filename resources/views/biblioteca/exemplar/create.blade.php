@extends('menu')

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h4>
                <i class="fa fa-user"></i>
                Cadastrar Exemplar
            </h4>
        </div>

        <div class="ibox-content">

            @if(Session::has('message'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <em> {!! session('message') !!}</em>
                </div>
            @endif

            @if(Session::has('errors'))
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            {!! Form::open(['route'=>'seracademico.biblioteca.storeExemplar', 'method' => "POST" , 'enctype' => 'multipart/form-data']) !!}
                @include('tamplatesForms.tamplateFormExemplar')
            {!! Form::close() !!}
        </div>
        <div class="ibox-footer">
            <span class="pull-right">
                footer a direita
            </span>
            footer esquerda
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function(){

            <?php
                $data = new \DateTime('now');
                $data = $data->format('d/m/Y');
            ?>
            var  data2 = '{{$data}}';
            $('.data2').val(data2);

            //consulta via select2 segunda entrada 1
            $("#obra").select2({
                placeholder: 'Selecione uma obra',
                minimumInputLength: 3,
                width: 400,
                ajax: {
                    type: 'POST',
                    url: "{{ route('seracademico.util.select2')  }}",
                    dataType: 'json',
                    delay: 250,
                    crossDomain: true,
                    data: function (params) {
                        return {
                            'search':     params.term, // search term
                            'tableName':  'bib_arcevos',
                            'columnSelect':  'titulo',
                            'fieldName':  'titulo',
                            'page':       params.page
                        };
                    },
                    headers: {
                        'X-CSRF-TOKEN' : '{{  csrf_token() }}'
                    },
                    processResults: function (data, params) {

                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                }
            });

            //consulta via select2 responsável
            $("#editora").select2({
                placeholder: 'Selecione uma editora',
                minimumInputLength: 3,
                width: 400,
                ajax: {
                    type: 'POST',
                    url: "{{ route('seracademico.util.select2')  }}",
                    dataType: 'json',
                    delay: 250,
                    crossDomain: true,
                    data: function (params) {
                        return {
                            'search':     params.term, // search term
                            'tableName':  'bib_editoras',
                            'fieldName':  'nome',
                            'page':       params.page
                        };
                    },
                    headers: {
                        'X-CSRF-TOKEN' : '{{  csrf_token() }}'
                    },
                    processResults: function (data, params) {

                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                }
            });


            function formatRepo(repo) {
                if (repo.loading) return repo.text;

                var markup = '<option value="' + repo.id + '">' + repo.name + '</option>';
                return markup;
            }

            function formatRepoSelection(repo) {

                return repo.name || repo.text
            }

            //consulta via select2 segunda entrada 1
            $("#responsavel").select2({
                placeholder: 'Selecione um editor',
                minimumInputLength: 3,
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: formatRepo,
                templateSelection: formatRepoSelection,
                width: 400,
                ajax: {
                    type: 'POST',
                    url: "{{ route('seracademico.util.select2personalizado')  }}",
                    dataType: 'json',
                    delay: 250,
                    crossDomain: true,
                    data: function (params) {
                        return {
                            'search': params.term, // search term
                            'tableName': 'responsaveis',
                            'fieldName': 'nome',
                            'page': params.page
                        };
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{  csrf_token() }}'
                    },
                    processResults: function (data, params) {

                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                }
            });

            //salvar editora
            $("#save").click(function (event) {
                event.preventDefault();
                var dados = {
                    'nome': $('#nome').val()
                }

                if($('#nome').val() != "") {
                    $.ajax({
                        url: "{{ route('seracademico.biblioteca.storeAjaxEditora')  }}",
                        data: {
                            dados: dados,
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{  csrf_token() }}'
                        },
                        dataType: "json",
                        type: "POST",
                        success: function (data) {
                            swal(data['msg'], "Click no botão abaixo!", "success");
                            $('#nome').val("")
                            //location.href = "/serbinario/calendario/index/";
                        }
                    });
                } else {
                    swal("O campo nome é obrigatório", "Click no botão abaixo!", "warning");
                }
            });

            //salvar responsável
            $("#saveRespo").click(function (event) {
                event.preventDefault();
                var dados = {
                    'nome': $('#nomeRespo').val(),
                    'sobrenome': $('#sobrenome').val(),
                }

                if($('#nomeRespo').val() != "" && $('#sobrenome').val() != "") {
                    $.ajax({
                        url: "{{ route('seracademico.biblioteca.storeAjaxResponsavel')  }}",
                        data: {
                            dados: dados,
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{  csrf_token() }}'
                        },
                        dataType: "json",
                        type: "POST",
                        success: function (data) {
                            swal(data['msg'], "Click no botão abaixo!", "success");
                            $('#nome').val("")
                            $('#sobrenome').val("")
                            //location.href = "/serbinario/calendario/index/";
                        }
                    });
                } else {
                    swal("Os campos nome e sobrenome são obrigatórios", "Click no botão abaixo!", "warning");
                }
            });

        });

        function maiuscula(id) {
            //palavras para ser ignoradas
            var wordsToIgnore = ["DOS", "DAS", "de", "do", "dos", "Dos", "Das", "das"],
                    minLength = 2;
            var str = $('#' + id).val();
            var getWords = function (str) {
                return str.match(/\S+\s*/g);
            }
            $('#' + id).each(function () {
                var words = getWords(this.value);
                $.each(words, function (i, word) {
                    // somente continua se a palavra nao estiver na lista de ignorados
                    if (wordsToIgnore.indexOf($.trim(word)) == -1 && $.trim(word).length > minLength) {
                        words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
                    } else {
                        words[i] = words[i].toLowerCase();
                    }
                });
                this.value = words.join("");
            });
        }
        ;

        //Deixar letra maiúscula
        $(document).ready(function ($) {
            // Chamada da funcao upperText(); ao carregar a pagina
            upperText();
            // Funcao que faz o texto ficar em uppercase
            function upperText() {

                // Para tratar o colar
                $("#sobrenome").bind('paste', function (e) {
                    var el = $(this);
                    setTimeout(function () {
                        var text = $(el).val();
                        el.val(text.toUpperCase());
                    }, 100);
                });

                // Para tratar quando é digitado
                $("#sobrenome").keypress(function () {
                    var el = $(this);
                    if (!el.hasClass('semCaixaAlta')) {
                        console.log('asdas');
                        setTimeout(function () {
                            var text = $(el).val();
                            el.val(text.toUpperCase());
                        }, 100);
                    }
                });
            }

        });
    </script>
@stop