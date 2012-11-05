<?php

namespace Fnx\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FuncionarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FuncionarioRepository extends EntityRepository {

    public function loadSalario($mes, $ano) {

        $inicio = new \DateTime($ano . "-" . $mes . "-01");
        $fim = new \DateTime($ano . "-" . $mes);

        $qb = $this->createQueryBuilder('f')
                ->select('f', 's', 'p')
                ->join('f.salario', 's')
                ->join('s.pagamentos',' p')
                ->where('f.tipo = :param')
                ->andWhere(("p.data between :inicio AND :fim"))
                ->setParameters(array("param" => "fun",
            "inicio" => $inicio,
            "fim" => $fim->format("Y-m-t")));

        return $qb->getQuery()->getArrayResult();
    }
    
    public function loadFuncionarioByUsuario($id){
        return $this->createQueryBuilder("f")
                ->select("f", "u")
                ->join("f.usuario ", "u")
                ->where("u.id = :id")
                ->setParameters(array("id" => $id))
                ->getQuery()
                ->getResult();
        
    }


}