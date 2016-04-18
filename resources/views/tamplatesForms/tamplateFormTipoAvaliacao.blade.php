<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    {!! Form::label('nome', 'Nome: *') !!}
                    {!! Form::text('nome', null, array('class' => 'form-control')) !!}

                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('codigo', 'Código: *') !!}
                    {!! Form::text('codigo', null, array('class' => 'form-control')) !!}
                </div>
            </div>
        </div>
        {{--Buttons Submit e Voltar--}}
        <div class="row">
            <div class="col-md-2">
                {!! Form::submit('Salvar', array('class' => 'btn btn-primary btn-block')) !!}
            </div>
            <div class="col-md-2">
                <a href="{{ route('seracademico.tipoAvaliacao.index') }}" class="btn btn-primary btn-block">Voltar</a>
            </div>
        </div>
        {{--Fim Buttons Submit e Voltar--}}

    </div>
</div>