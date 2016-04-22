<?php

namespace Seracademico\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class CurriculoValidator extends LaravelValidator
{
	use TraitReplaceRulesValidator;

	protected $messages   = [
	];

	protected $attributes = [
	];

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [

			'nome' =>  'required|max:200|unique:fac_curriculos,nome',
			'codigo' =>  'required|max:15|unique:fac_curriculos,codigo',
			'ano' =>  'digits_between:1,4|numeric' ,
			'valido_inicio' =>  '' ,
			'valido_fim' =>  '' ,
			'curso_id' =>  '' ,

        ],
        ValidatorInterface::RULE_UPDATE => [

			'nome' =>  'required|max:200|unique:fac_curriculos,nome,:id',
			'codigo' =>  'required|max:15|unique:fac_curriculos,codigo,:id',
			'ano' =>  'digits_between:1,4|numeric' ,
			'valido_inicio' =>  '' ,
			'valido_fim' =>  '' ,
			'curso_id' =>  '' ,
		],
   ];

}
