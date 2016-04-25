<?php

namespace Seracademico\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class SegundaEntrada extends Model implements Transformable
{
    use TransformableTrait;

    protected $table    = 'segunda_entrada';

    protected $fillable = [ 
		'tipo_autor_id',
		'arcevos_id',
		'responsaveis_id',
	];

	public function tipoAutor()
	{
		return $this->belongsTo(TipoAutor::class, 'tipo_autor_id');
	}

	public function acervos()
	{
		return $this->belongsTo(Arcevo::class, 'arcevos_id');
	}

	public function responsaveis()
	{
		return $this->belongsTo(Responsavel::class, 'responsaveis_id');
	}

}