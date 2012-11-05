<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EscalaFunRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EscalaFunRepository extends EntityRepository
{
       public function loadEscalaFun($status, $servico){
        
//        $inicio = new \DateTime($this->conv_data_to_us($inicio));   
//        $fim = new \DateTime($this->conv_data_to_us($fim));
           
            $qb = $this->createQueryBuilder('e')
                  ->select('e','f','s')
                  ->innerJoin('e.funcionarios', 'f')
                  ->innerJoin('e.servicoEscala', 's');

            if ($status == "a"){
                $qb->where("e.ativo = 1");

            }elseif ($status == "c"){
                $qb->where("e.ativo = 0");
            }

            if ($servico != 0){
                $qb->andWhere("s.id = :servico")
                ->setParameter("servico", $servico);
            }

            return $qb->getQuery()->getArrayResult();
                 
       }
       
  public function loadEscalaFunTurno(){
        
        return $this->getEntityManager()
                ->createQuery('SELECT f,t
                               FROM FnxAdminBundle:Funcionario f
                               JOIN f.tipo t
                               WHERE t.id <> ?1')
                ->setParameter(1, 2)
                ->getResult();
       }
       
    public function verificaEscala($id, $dataInicial, $dataFinal){
       
        return $this->getEntityManager()
                ->createQuery('SELECT e FROM FnxAdminBundle:EscalaFun e 
                               JOIN e.funcionarios f
                               WHERE e.inicio <= :final AND e.fim >= :inicial
                               AND f.id = :id')
                ->setParameters(array("inicial" => $dataInicial,
                                      "final" => $dataFinal,
                                      "id" => $id,
                                ))
                ->getResult();
    }
    
    public function loadEscalaPatru($data, $objeto = false){
            $inicio = new \DateTime(substr($this->conv_data_to_us($data),0,10)." 00:00:00");
            $fim = new \DateTime(substr($this->conv_data_to_us($data),0,10)." 23:59:00");
            
            $qb = $this->createQueryBuilder('e')
                  ->select('e','f','s')
                  ->innerJoin('e.funcionarios', 'f')
                  ->innerJoin('e.servicoEscala', 's')
                  ->where("s.nome = :nome")
                  ->andWhere(("e.inicio between :inicio and :fim " ))
                  ->setParameters(array("nome" => "Patrulhamento",
                                        "inicio" => $inicio,
                                        "fim" => $fim))
                  ->getQuery();
              
            if ($objeto == false){
                return $qb->getArrayResult();
            }else{
                return $qb->getResult();
            }
                  
                 
       }
       
    public function locaEscalaByData($dataCliente){
        $inicio = new \DateTime(substr($this->conv_data_to_us($dataCliente),0,10)." 00:00:00");
        $fim = new \DateTime(substr($this->conv_data_to_us($dataCliente),0,10)." 23:59:00");
        
        $data = $this->getEntityManager()->createQuery("SELECT max(e.inicio) FROM FnxAdminBundle:EscalaFun e")
                                         ->getResult();
        
        $dataObject = new \DateTime($data[0][1]);
        if ($dataObject >= $inicio && $dataObject <= $fim){
            $dataObject->modify("-1 days");
        }
        
        return $this->loadEscalaPatru($this->conv_data_to_br($dataObject->format("Y-m-d")), true);
        
    }
    
    

    public static function conv_data_to_us($date){
	$dia = substr($date,0,2);
	$mes = substr($date,3,2);
	$ano = substr($date,6,4);
        $hora = substr($date, 11,2);
        $min = substr($date, 14,2);
	return "{$ano}-{$mes}-{$dia} {$hora}:{$min} ";
    }
    
    public static function conv_data_to_br($date){
        $ano = substr($date,0,4);
        $mes = substr($date,5,2);
        $dia = substr($date,8,2);
        return "{$dia}/{$mes}/{$ano}";
        
    }
}   