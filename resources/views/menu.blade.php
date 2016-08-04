<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestão Acadêmica</title>

    <link href="{{ asset('/css/bootstrap.min.css')}}" rel="stylesheet">
    {{--<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">--}}

    <link href="{{ asset('/fonts/iconfont/material-icons.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900,300" rel="stylesheet">
    <link href="{{ asset('/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/style.css')}}" rel="stylesheet">

    <link href="{{ asset('/css/jquery-ui.css')}}" rel="stylesheet">
    {{--<link href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">--}}

    <link href="{{ asset('/css/jquery.tree.css')  }}" rel="stylesheet">
    <link href="{{ asset('/css/jasny-bootstrap.css')  }}" rel="stylesheet">
    <link href="{{ asset('/css/awesome-bootstrap-checkbox.css')  }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrapValidation.mim.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/jquery.datetimepicker.css')}}" rel="stylesheet"/>

    <link href="{{ asset('/css/jquery.dataTables.min.css')}}" rel="stylesheet"/>
    {{--<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">--}}

    <link href="{{ asset('/css/buttons.dataTables.min.css')}}" rel="stylesheet"/>
    {{--<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">--}}

    <link rel="stylesheet" href="{{ asset('/css/plugins/sweetalert/sweetalert.css')  }}">
    <link rel="stylesheet" href="{{ asset('/css/plugins/botao/botao-fab.css')  }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-multiselect.css')  }}">

    <!-- zTree-->
    <link rel="stylesheet" href="{{ asset('/css/plugins/zTree/zTreeStyle.css')  }}">
    {{--<link rel="stylesheet" href="{{ asset('/css/plugins/zTree/demo.css')  }}">--}}

    @yield('css')
</head>

<body>

<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    @role('ouvidoria')
                    <img alt="image" class="logoDash" src="{{ asset('/img/ouvidoria_saude_igarassu.png')}}"/>
                    @endrole
                    @role('biblioteca')
                    <img alt="image" class="logoDash" src="{{ asset('/img/logoser2.png')}}"/>
                    @endrole
                </li>

                @role('admin')
                <li>
                    <a href="index.html"><i class="material-icons">lock</i> <span class="nav-label">Segurança</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('seracademico.user.index') }}"><i class="material-icons">account_circle</i> Usuários</a></li>
                        <li><a href="{{ route('seracademico.role.index') }}"><i class="material-icons">account_box</i> Perfís</a></li>
                    </ul>
                </li>
                @endrole

                @role('biblioteca')
                <li>
                    <a href="index.html"><i class="fa fa-book"></i> <span class="nav-label"> Biblioteca</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('seracademico.biblioteca.indexResponsavel') }}"><i class="material-icons">perm_identity</i> Responsável</a></li>
                        <li><a href="{{ route('seracademico.biblioteca.indexEditora') }}"><i class="material-icons">card_travel</i> Editora</a></li>
                        <li><a href="{{ route('seracademico.biblioteca.indexAcervo') }}"><i class="material-icons">find_in_page</i> Acervo</a></li>
                        <li><a href="{{ route('seracademico.biblioteca.indexExemplar') }}"><i class="material-icons">receipt</i> Exemplar</a></li>
                        <li>
                            <a href="#">Controle <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li><a href="{{ route('seracademico.biblioteca.indexEmprestimo') }}">Emprestimo</a></li>
                                <li><a href="{{ route('seracademico.biblioteca.viewDevolucaoEmprestimo') }}">Devolução</a></li>
                                <li><a href="{{ route('seracademico.biblioteca.indexReserva') }}">Reservar</a></li>
                                <li><a href="{{ route('seracademico.biblioteca.reservados') }}">Reservas</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('indexConsulta') }}" target="__blank"><i class="material-icons">receipt</i> Consulta</a></li>
                        <li><a href="{{ route('seracademico.biblioteca.indexPessoa') }}"><i class="material-icons">receipt</i> Pessoa</a></li>
                    </ul>
                </li>
                <li>
                    <a href="index.html"><i class="fa fa-book"></i> <span class="nav-label"> Dashboards</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('seracademico.biblioteca.dashboardBliblioteca') }}"><i class="material-icons">perm_identity</i> Dashboard Biblioteca</a></li>
                    </ul>
                </li>
               @endrole
               @role('ouvidoria')
                @role('ouvidoria|posgraduacao')
                <li>
                    <a href="index.html"><i class="fa fa-book"></i> <span class="nav-label"> Ouvidoria</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('seracademico.ouvidoria.demanda.index') }}"><i class="material-icons">perm_identity</i> Demanda</a></li>
                    </ul>
                </li>
                @endrole
                <li>
                    <a href="index.html"><i class="fa fa-book"></i> <span class="nav-label"> Relatório</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('seracademico.ouvidoria.report.reportPessoas') }}" target="_blank"><i class="material-icons">perm_identity</i> Pessoas</a></li>
                        <li><a href="{{ route('seracademico.ouvidoria.report.viewReportStatus') }}"><i class="material-icons">perm_identity</i> Status</a></li>
                    </ul>
                </li>
                <li>
                    <a href="index.html"><i class="fa fa-book"></i> <span class="nav-label"> Gráficos</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('seracademico.ouvidoria.graficos.caracteristicas') }}" target="_blank"><i class="material-icons">perm_identity</i> Características</a></li>
                        <li><a href="{{ route('seracademico.ouvidoria.graficos.assunto') }}" target="_blank"><i class="material-icons">perm_identity</i> Assuntos</a></li>
                        <li><a href="{{ route('seracademico.ouvidoria.graficos.subassunto') }}" target="_blank"><i class="material-icons">perm_identity</i> Subassuntos</a></li>
                        <li><a href="{{ route('seracademico.ouvidoria.graficos.meioRegistro') }}" target="_blank"><i class="material-icons">perm_identity</i> Meios de registro</a></li>
                        <li><a href="{{ route('seracademico.ouvidoria.graficos.perfil') }}" target="_blank"><i class="material-icons">perm_identity</i> Perfies</a></li>
                        <li><a href="{{ route('seracademico.ouvidoria.graficos.escolaridade') }}" target="_blank"><i class="material-icons">perm_identity</i> Escolaridade</a></li>
                        <li><a href="{{ route('seracademico.ouvidoria.graficos.idade') }}" target="_blank"><i class="material-icons">perm_identity</i> Idades</a></li>
                    </ul>
                </li>
                <li><a href="index.html"><i class="material-icons">lock</i> <span class="nav-label">Segurança</span> <span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('seracademico.user.index') }}"><i class="material-icons">account_circle</i> Usuários</a></li>
                        <li><a href="{{ route('seracademico.role.index') }}"><i class="material-icons">account_box</i> Perfís</a></li>
                    </ul>
                </li>
               @endrole
                @role('admin')
                    <li><a href="index.html"><i class="material-icons">lock</i> <span class="nav-label">Segurança</span> <span
                                    class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="{{ route('seracademico.user.index') }}"><i class="material-icons">account_circle</i> Usuários</a></li>
                            <li><a href="{{ route('seracademico.role.index') }}"><i class="material-icons">account_box</i> Perfís</a></li>
                        </ul>
                    </li>
                @endrole
            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i>
                    </a>
                </div>
                <div class="profile-img">
                    <span>
                        @if(isset(Session::get("user")['img']))
                            <img alt="image" class="img-circle" src="{{asset('/uploads/fotos/'.Session::get("user")['img'])}}" alt="Foto"  height="50" width="50">
                        @endif
                    </span>
                </div>

                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="text-muted text-xs block">Idioma<b class="caret"></b></span></a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <li>
                                        <a rel="alternate" hreflang="{{$localeCode}}" href="{{LaravelLocalization::getLocalizedURL($localeCode) }}">
                                            {{{ $properties['native'] }}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                </ul>

                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <div class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="text-muted text-xs block">{{ Auth::user()->name }}<b class="caret"></b></span></a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                {{-- <li><a href="profile.html">Perfil</a></li>
                                 <li><a href="contacts.html">Notificações</a></li>--}}
                                {{--<li class="divider"></li>--}}
                                <li><a href="{{ url('auth/logout') }}">Sair</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mainly scripts -->
<script src="{{ asset('/js/jquery-2.1.1.js')}}"></script>
<script src="{{ asset('/js/jquery-ui.js')}}"></script>
<script src="{{ asset('/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{ asset('/js/plugins/toastr.min.js')}}"></script>
<script src="{{ asset('/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{ asset('/js/bootstrapvalidator.js')}}" type="text/javascript"></script>
<script src="{{ asset('/js/jquery.tree.js')}}" type="text/javascript"></script>
<script src="{{ asset('/js/jquery.datetimepicker.js')}}" type="text/javascript"></script>
<script src="{{ asset('/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<script src="{{ asset('/js/bootbox.min.js')}}" type="text/javascript"></script>

<script src="{{ asset('/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>

<script src="{{ asset('/vendor/datatables/buttons.server-side.js') }}"></script>

{{--<script src="{{ asset('/js/jquery.datatables.customsearch.js')}}" type="text/javascript"></script>--}}

<!-- zTree -->
<script src="{{ asset('/js/plugins/zTree/jquery.ztree.core.min.js')}}"></script>

<!-- Custom and plugin javascript -->
<script src="{{ asset('/js/inspinia.js')}}"></script>
<script src="{{ asset('/js/plugins/pace/pace.min.js')}}"></script>
<script src="{{ asset('/js/jasny-bootstrap.js')}}"></script>
<script src="{{ asset('/js/jquery.mask.js')}}"></script>
<script src="{{ asset('/js/mascaras.js')}}"></script>
<script src="{{ asset('/js/sb-admin-2.js')}}"></script>
<script src="{{ asset('/messages.js')}}"></script>
<script src="{{ asset('/js/plugins/sweetalert/sweetalert.min.js')  }}"></script>
<script src="{{ asset('/js/plugins/botao/materialize.min.js')  }}"></script>
<script type="text/javascript">
    $(document).on({
        'show.bs.modal': function () {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        },
        'hidden.bs.modal': function() {
            if ($('.modal:visible').length > 0) {
                // restore the modal-open class to the body element, so that scrolling works
                // properly after de-stacking a modal.
                setTimeout(function() {
                    $(document.body).addClass('modal-open');
                }, 0);
            }
        }
    }, '.modal');
</script>

@yield('javascript')
</body>

</html>