<?php

namespace Seracademico\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Seracademico\Validators\SegundaEntradaValidator;
use Seracademico\Repositories\SegundaEntradaRepository;
use Seracademico\Entities\SegundaEntrada;

/**
 * Class SegundaEntradaRepositoryEloquent
 * @package namespace App\Repositories;
 */
class SegundaEntradaRepositoryEloquent extends BaseRepository implements SegundaEntradaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SegundaEntrada::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

         return SegundaEntradaValidator::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
