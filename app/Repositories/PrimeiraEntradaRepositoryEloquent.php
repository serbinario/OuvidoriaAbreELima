<?php

namespace Seracademico\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Seracademico\Validators\PrimeiraEntradaValidator;
use Seracademico\Repositories\PrimeiraEntradaRepository;
use Seracademico\Entities\PrimeiraEntrada;

/**
 * Class PrimeiraEntradaRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PrimeiraEntradaRepositoryEloquent extends BaseRepository implements PrimeiraEntradaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PrimeiraEntrada::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

         return PrimeiraEntradaValidator::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
