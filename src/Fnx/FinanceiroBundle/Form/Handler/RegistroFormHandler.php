<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\FinanceiroBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormError;
use Fnx\FinanceiroBundle\Entity\Registro;
use Fnx\FinanceiroBundle\Entity\Parcela;
use Fnx\FinanceiroBundle\Entity\Movimentacao;

/**
 * Description of RegistroFormHandler
 *
 * @author bondcs
 */
class RegistroFormHandler {
    
    protected $em;
    protected $form;
    protected $request;
    
    public function __construct(Form $form, Request $request, EntityManager $em) {
        $this->em = $em;
        $this->form = $form;
        $this->request = $request;
    }
    
    public function process(Registro $registro){

        if ($this->request->getMethod() == "POST"){
            $this->form->bindRequest($this->request);
            if ($this->form->isValid()){
                $this->onSuccess($registro);
                return true;
            }
        }
        return false;
    }
    
    public function onSuccess(Registro $registro){
        
        $registro->setConta($this->form["conta"]->getData());
        $categoria = $this->em->createQuery("SELECT c FROM FnxAdminBundle:Categoria c WHERE c.nome = :param")->setParameter("param", "Atividade")->getSingleResult();
        $registro->setCategoria($categoria);
        $primeiroVencimento = $this->form["primeiraParcela"]->getData();
        $formato = $primeiroVencimento->format("Y-m-d");
        $registro->setPrimeiraParcela($primeiroVencimento);
        $registro->setValor($this->form["valor"]->getData());
        for ($i = 0; $i < $this->form["numeroParcela"]->getData(); $i++){
             $vencimento = new \Datetime($formato);
             $parcela = new Parcela();
             $parcela->setDtVencimento($vencimento->modify('+'.$i.' month'));
             $parcela->setRegistro($registro);
             $parcela->setNumero($i+1);
             $movimentacao = new Movimentacao();
             $movimentacao->setFormaPagamento($this->form['formaPagamento']->getData());
             $movimentacao->setParcela($parcela);
             $movimentacao->setMovimentacao('r');
             $movimentacao->setValor($registro->calculaParcela($this->form["numeroParcela"]->getData(), $registro->getValor()));
             $registro->addParcela($parcela);
             $parcela->setMovimentacao($movimentacao);
        }
        
        $conta = $this->form["conta"]->getData();
        $conta->addRegistro($registro);
        //var_dump($registro->getParcelas()->get(0)->getMovimentacao()->getValor());die();
        $this->em->persist($registro);
        $this->em->flush();
    }
    
    
}

?>
