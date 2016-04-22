<?php

namespace Seracademico\Services;

use Seracademico\Repositories\CurriculoRepository;
use Seracademico\Repositories\TurmaRepository;
use Seracademico\Entities\Turma;
use Carbon\Carbon;

class TurmaService
{
    /**
     * @var TurmaRepository
     */
    private $repository;

    /**
     * @var CurriculoRepository
     */
    private $curriculoRepository;

    /**
     * @param TurmaRepository $repository
     * @param CurriculoRepository $curriculoRepository
     */
    public function __construct(TurmaRepository $repository, CurriculoRepository $curriculoRepository)
    {
        $this->repository          = $repository;
        $this->curriculoRepository = $curriculoRepository;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function find($id)
    {
        #Recuperando o registro no banco de dados
        $turma = $this->repository->find($id);

        #Verificando se o registro foi encontrado
        if(!$turma) {
            throw new \Exception('Empresa não encontrada!');
        }

        #retorno
        return $turma;
    }

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data) : Turma
    {
        #Aplicação das regras de negócios
        $this->tratamentoDoCurso($data);
        $this->tratamentoDatas($data);

        #Salvando o registro pincipal
        $turma =  $this->repository->create($data);

        #Verificando se foi criado no banco de dados
        if(!$turma) {
            throw new \Exception('Ocorreu um erro ao cadastrar!');
        }

        #Aplicação das regras de negócios
        $this->tratamentoDisciplinas($turma);

        #Retorno
        return $turma;
    }

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id) : Turma
    {
        # Aplicação das regras de negócios
        $this->tratamentoDatas($data);
        $this->tratamentoDoCurso($data, $id);

        # Verifica se é o mesmo currículo (false), se não for, se pode ser alterado (true).
        # Se não poder ser alterado lançará uma exception.
        $resultTratamentoCurriculo = $this->tratamentoCurriculo($id, $data);

        # Atualizando no banco de dados
        $turma = $this->repository->update($data, $id);

        #Verificando se foi atualizado no banco de dados
        if (!$turma) {
            throw new \Exception('Ocorreu um erro ao cadastrar!');
        }

        # Verifica se é um currículo diferente.
        # true -> currículo diferente e válido para ser alterado
        # false -> currículo igual
        if ($resultTratamentoCurriculo) {
            # Aplicação das regras de negócios
            $this->tratamentoDisciplinasUpdate($turma);
        }

        # Retorno
        return $turma;
    }

    /**
     * @param array $models
     * @return array
     */
    public function load(array $models) : array
    {
        #Declarando variáveis de uso
        $result    = [];
        $expressao = [];

        #Criando e executando as consultas
        foreach ($models as $model) {
            # separando as strings
            $explode   = explode("|", $model);

            # verificando a condição
            if(count($explode) > 1) {
                $model     = $explode[0];
                $expressao = explode(",", $explode[1]);
            }

            #qualificando o namespace
            $nameModel = "\\Seracademico\\Entities\\$model";

            if(count($expressao) > 1) {
                #Recuperando o registro e armazenando no array
                $result[strtolower($model)] = $nameModel::situacao(1)->lists('nome', 'id');
            } else {
                #Recuperando o registro e armazenando no array
                $result[strtolower($model)] = $nameModel::lists('nome', 'id');
            }

            # Limpando a expressão
            $expressao = [];
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
        $data['matricula_inicio'] = $data['matricula_inicio'] ? Carbon::createFromFormat("d/m/Y", $data['matricula_inicio']) : "";
        $data['matricula_fim']    = $data['matricula_fim'] ? Carbon::createFromFormat("d/m/Y", $data['matricula_fim']) : "";
        $data['aula_inicio']      = $data['aula_inicio'] ? Carbon::createFromFormat("d/m/Y", $data['aula_inicio']) : "";
        $data['aula_final']       = $data['aula_final'] ? Carbon::createFromFormat("d/m/Y", $data['aula_final']) : "";

        #retorno
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    private function tratamentoDoCurso(&$data, $id = "")
    {
        #Verificando se foi passado o curso
        if($data['curso_id']) {
            #Recuperando o currículo ativo
            $curriculo = $this->curriculoRepository->getCurriculoAtivo($data['curso_id']);

            # Verificando se existe currículo
            if(!$curriculo) {
                throw new \Exception("Não existe currículo ativo vinculado a esse curso.");
            }

            #verificando se foi passado o id (caso seja update)
            if($id) {
                #Recuperando o objeto turma do banco de dados
                $objTurmaBanco      = $this->repository->find($id);
                $objCurriculoUpdate = $this->curriculoRepository->find($curriculo[0]->id);

                # Verificando se o o curso do currículo do banco
                # é o mesmo do currículo passado
                if($objTurmaBanco->curriculo->curso->id == $objCurriculoUpdate->curso->id) {
                    #Permanecendo o currículo do banco de dados (Que já estava vinculado na turma)
                    $data['curriculo_id'] = $objTurmaBanco->curriculo->id;

                    #retorno
                    return false;
                }
            }

            #Verificando se existe o currículo ativo
            if(count($curriculo) > 0) {
                $data['curriculo_id'] = $curriculo[0]->id;
                unset($data['curso_id']);
            } else {
                throw new \Exception("Não existe currículo ativo para esse curso!");
            }
        }

        #retorno
        return $data;
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * @throws \Exception
     */
    private function tratamentoCurriculo($id, $data)
    {
        #Recuperando o objeto turma do banco de dados
        $objTurmaBanco = $this->repository->find($id);

        #Verificando se é o mesmo currículo
        if($objTurmaBanco->curriculo_id == $data['curriculo_id']) {
            #retorno
            return false;
        }

        #percorrendo as disciplinas
        foreach ($objTurmaBanco->disciplinas as $disciplina) {
            if(count($disciplina->pivot->calendarios) > 0) {
                throw new \Exception("Já existe calendários para a esse curso,
                 se quiser continuar com a operação deverá deletar os calendários criados para esse curso!");
            }
        }


        #retorno
        return true;
    }

    /**
     * @param Turma $turma
     * @throws \Exception
     */
    private function tratamentoDisciplinas(Turma $turma)
    {
        #Verificando se disciplinas vinculadas ao currículo
        if(!count($turma->curriculo->disciplinas) > 0) {
            #retorno se não tiver disciplinas vinculadas ao currículo
            return false;
        }

        #percorrendo as disciplinas
        foreach ($turma->curriculo->disciplinas as $disciplina) {
            $turma->disciplinas()->attach($disciplina);
        }

        #Salvando no as disciplinas
        try {
            $turma->save();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        #Retorno se tudo der certo
        return true;
    }

    /**
     * @param Turma $turma
     * @return bool
     * @throws \Exception
     */
    private function tratamentoDisciplinasUpdate(Turma $turma)
    {
        #Deleta todas as disciplinas vinculadas a essa turma
        $turma->disciplinas()->detach();

        #retorno
        return $this->tratamentoDisciplinas($turma);
    }
}