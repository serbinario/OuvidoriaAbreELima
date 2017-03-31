<html>
<head>
    <link href="{{ asset('/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,900,300" rel="stylesheet">
    <link href="{{ asset('/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrapValidation.mim.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/jquery.datetimepicker.css')}}" rel="stylesheet"/>
</head>
<body>
<div class="conteiner">
    <div class="row">
        <center>
            <div class="topo" style="background-color: #213a53">
                <center>
                    <img src="{{asset('/img/ouvidoria-logo.png')}}" style="width: 500px; height: 150px">
                    {{--<img src="{{asset('/img/igarassu.png')}}" style="width: 400px; height: 90px">--}}
                </center>
            </div>
        </center>
    </div>

    <br />
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <a href="{{ route('createPublico')  }}" title="cadastro da manifestação">
                <img src="{{asset('/img/TagInicial_02.png')}}" style="margin-left: 25px">
            </a>
            <a href="{{ route('buscarDemanda')  }}" title="consultar da manifestação">
                <img src="{{asset('/img/TagInicial_03.png')}}" >
            </a>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>

<script src="{{ asset('/js/jquery-2.1.1.js')}}"></script>
<script src="{{ asset('/js/jquery-ui.js')}}"></script>
<script src="{{ asset('/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/jquery.mask.js')}}"></script>
<script src="{{ asset('/js/mascaras.js')}}"></script>
<script src="{{ asset('/js/bootstrapvalidator.js')}}" type="text/javascript"></script>
<script src="{{ asset('/js/jquery.datetimepicker.js')}}" type="text/javascript"></script>
<script src="{{ asset('/js/validacoes/validation_form_demanda.js')}}"></script>

</body>
</html>