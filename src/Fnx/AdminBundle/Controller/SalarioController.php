<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Fnx\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Fnx\AdminBundle\Entity\Atividade;
use Fnx\AdminBundle\Entity\Contrato;
use Fnx\AdminBundle\Form\Type\AtividadeType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Fnx\AdminBundle\Form\Type\FuncionarioType;
use Fnx\AdminBundle\Entity\Funcionario;
use Fnx\AdminBundle\Entity\SalarioPagamento;
use Fnx\AdminBundle\Entity\Salario;
use Fnx\AdminBundle\Form\Type\SalarioType;

/**
 * Description of SalarioController
 *
 * @author bondcs
 * @Route("/adm/funcionario/salario")
 */
class SalarioController extends Controller{
    
    
    /**
     * @Route("/", name="salarioHome")
     * @Template()
     */
    public function indexAction(){
        
//        $dbal = $this->get('database_connection');
//        $stmt = $dbal->query('SELECT * FROM atividade');
//        var_dump($stmt->fetchAll());die()
        $formFilter = $this->createForm(new \Fnx\FinanceiroBundle\Form\Type\FilterType());
        return array("formFilter" => $formFilter->createView());
        
    }
    
    /**
     * @Route("/edit/{pagamentoId}", name="funcionarioSalarioEdit", options={"expose" = true})
     * @Template()
     */
    public function editAction($pagamentoId){
        
        $em = $this->getDoctrine()->getEntityManager();
        $pagamento = $em->find("FnxAdminBundle:SalarioPagamento", $pagamentoId);
        if (!$pagamento){
            throw $this->createNotFoundException("Pagamento não encontrado.");
        }
        
        $request = $this->getRequest();
        if ($request->getMethod() == "POST"){
                $valor =  substr(str_replace(",", ".", $this->get('request')->request->get('valor')),3);
                $pagamento->setBonus($pagamento->getBonus() + $valor);
                $em->persist($pagamento);
                $em->flush();
                
                $responseSuccess = array(
                  'dialogName' => '.simpleDialog',
                  'message' => 'edit'
                );

                $response = new Response(json_encode($responseSuccess));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
        }
        
        return array("pagamentoId" => $pagamentoId);
    }
    
    /**
     * @Route("/salario-pagamento/{mes}", name="salarioPagamento", options={"expose" = true})
     * @Template()
     */
    public function pagamentoAction($mes){
        
        $pagamentos = $this->get("request")->request->get("pagamentos");
        $response = array();
        if (count($pagamentos) <= 0){
            $response = array('notifity' => 'noSelected');
        }else{ 
            $em = $this->getDoctrine()->getEntityManager();
            $config = $this->getConfig();
            $categoria = $em->createQuery("SELECT c FROM FnxAdminBundle:Categoria c WHERE c.nome = :param")->setParameter("param", "Salario")->getSingleResult();
            
            foreach ($pagamentos as $pagamentoId){
                $pagamento = $em->find("FnxAdminBundle:SalarioPagamento", $pagamentoId);
                $pagamento->getSalario()->setUltimoPagamento(new \DateTime());
                $pagamento->setValorPago($pagamento->calculaSalario($config->getValorDependente()));
                $pagamento->setDataPagamento(new \DateTime());
                $pagamento->setPago(true);
                if ($pagamento->efetuaPagamento($config->getContaSalario(), $categoria, $config->getValorDependente(), $config->getFormaPagamentoSalario(), $mes)){
                    $em->persist($pagamento);
                    $em->flush();
                    $response = array('notifity' => 'add');
                }else{
                    $response = array('notifity' => 'erroSaldo');
                    break;
                }
            }
            
        }
        
        return $this->responseAjax($response);
    }
    
    /**
     * @Route("/salario-gerar-pagamento/{mes}/{ano}", name="salarioGerarPagamento", options={"expose" = true})
     * @Template()
     */
    public function gerarAction($mes,$ano){
        
//        $inicio = new \DateTime($ano . "-" . $mes . "-01");
//        $fimInicial = new \DateTime($ano . "-" . $mes);
//        $fim = new \DateTime($fimInicial->format("Y-m-t"));
        $data = new \DateTime($ano."-".$mes."-10");
        
        $funcionarios = $this->getDoctrine()->getRepository("FnxAdminBundle:Funcionario")->findBy(array("tipo" => "fun"));

        $flag = false;
        $em = $this->getDoctrine()->getEntityManager();
        foreach ($funcionarios as $funcionario){
            if ($funcionario->getSalario()->verificaPagamentos($mes, $ano)){
                $salario = $funcionario->getSalario();
                $salarioPagamento = new SalarioPagamento();
                $salarioPagamento->setSalario($salario);
                $salarioPagamento->setData($data);
                $salario->addSalarioPagamento($salarioPagamento);
                $em->persist($funcionario);
                $flag = true;
            }
        }
        
        $em->flush();
        $response = $flag ? array('notifity' => 'gerar') : array('notifity' => 'gerado');
        return $this->responseAjax($response);
    }
    
    /**
     * @Route("/ajaxSalario/{mes}/{ano}", name="escalaSalario", options={"expose" = true})
     * @Template()
     */
    public function ajaxAction($mes, $ano){
        
        $config = $this->getConfig();
        $salariosBanco = $this->getDoctrine()->getRepository("FnxAdminBundle:Funcionario")->loadSalario($mes,$ano);
        $salarios['aaData'] = array();
        
        foreach ($salariosBanco as $key => $value) {
            $value['salario']['pagamento']['bonus'] = $value['salario']['pagamentos'][0]['bonus'];
            $value['salario']['pagamento']['id'] = $value['salario']['pagamentos'][0]['id'];
            $value['salario']['pagamento']['pago'] = $value['salario']['pagamentos'][0]['pago'];
            $value['salario']['ultimoPagamento'] = $value['salario']['ultimoPagamento'] ? $value['salario']['ultimoPagamento']->format('d/m/Y H:i:s')." (".$value['salario']['ultimoPagamento']->diff(new \Datetime)->d." dias)": "-";
            $value['valorDependente'] = $config->getValorDependente();
            $salarios['aaData'][] = $value;
        }
       
        $response = new Response(json_encode($salarios));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
    public function responseAjax($json){
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function getConfig(){
        $entity = $this->getDoctrine()->getRepository('FnxAdminBundle:Configuracao')->findAll();
        return $entity[0];
    }
}

?>
