<?php

namespace Seracademico\Services;

use Seracademico\Repositories\EditoraRepository;
use Seracademico\Entities\Editora;
use Seracademico\Repositories\EnderecoRepository;

//use Carbon\Carbon;

class EditoraService
{
    /**
     * @var EditoraRepository
     */
    private $repository;

    /**
     * @var EnderecoRepository
     */
    private $enderecoRepository;

    /**
     * @param EditoraRepository $repository
     */
    public function __construct(EditoraRepository $repository, EnderecoRepository $enderecoRepository)
    {
        $this->repository = $repository;
        $this->enderecoRepository = $enderecoRepository;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function find($id)
    {
        $relacionamentos = [
            'endereco.bairro.cidade.estado',
        ];

        #Recuperando o registro no banco de dados
        $editora = $this->repository->with($relacionamentos)->find($id);

        #Verificando se o registro foi encontrado
        if(!$editora) {
            throw new \Exception('Empresa não encontrada!');
        }

        #retorno
        return $editora;
    }

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data) : Editora
    {

        $this->tratamentoCampos($data);

        #Criando no banco de dados
        $endereco = $this->enderecoRepository->create($data['endereco']);

        //dd($endereco);

        #setando o endereco
        $data['enderecos_id'] = $endereco->id;

        #Salvando o registro pincipal
        $editora =  $this->repository->create($data);

        #Verificando se foi criado no banco de dados
        if(!$editora) {
            throw new \Exception('Ocorreu um erro ao cadastrar!');
        }

        #Retorno
        return $editora;
    }

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id) : Editora
    {
        $this->tratamentoCampos($data);

        #Atualizando no banco de dados
        $editora = $this->repository->update($data, $id);


        #Verificando se foi atualizado no banco de dados
        if(!$editora) {
            throw new \Exception('Ocorreu um erro ao cadastrar!');
        }

        #Retorno
        return $editora;
    }

    /**
     * @param array $models
     * @return array
     */
    public function load(array $models) : array
    {
        #Declarando variáveis de uso
        $result = [];

        #Criando e executando as consultas
        foreach ($models as $model) {
            #qualificando o namespace
            $nameModel = "Seracademico\\Entities\\$model";

            #Recuperando o registro e armazenando no array
            $result[strtolower($model)] = $nameModel::lists('nome', 'id');
        }

        #retorno
        return $result;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function tratamentoDatas(array &$data) : array
    {
         #tratando as datas
         //$data[''] = $data[''] ? Carbon::createFromFormat("d/m/Y", $data['']) : "";

         #retorno
         return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    public function tratamentoCampos(array &$data)
    {
        # Tratamento de campos de chaves estrangeira
        foreach ($data['endereco'] as $key => $value) {
            $explodeKey = explode("_", $key);

            if ($explodeKey[count($explodeKey) -1] == "id" && $value == null ) {
                unset($data['endereco'][$key]);
            }
        }
        #Retorno
        return $data;
    }

}