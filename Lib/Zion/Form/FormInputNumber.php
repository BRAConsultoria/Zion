<?php

namespace Zion\Form;

class FormInputNumber extends \Zion\Form\FormBasico
{
    private $tipoHtml;
    private $acao;
    private $valorMaximo;
    private $valorMinimo;
    
    public function __construct($acao)
    {
        $this->tipoHtml = 'number';
        $this->acao = $acao;
    }
    
    public function getTipoHtml()
    {
        return $this->tipoHtml;
    }
    
    public function getAcao()
    {
        return $this->acao;
    }
    
    public function setValorMaximo($valorMaximo)
    {
        $this->valorMaximo = $valorMaximo;
        return $this;
    }
    
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    public function setValorMinimo($valorMinimo)
    {
        $this->valorMinimo = $valorMinimo;
        return $this;
    }
    
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }
    
    /**
     * Sobrecarga de Metodos Básicos
     */    
        public function setId($id)
    {
        parent::setId($id);        
        return $this;
    }
    
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }
    
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);       
        return $this;
    }
    
    public function setValor($valor)
    {
        parent::setValor($valor);      
        return $this;
    }
    
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);        
        return $this;
    }
    
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);     
        return $this;
    }
    
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);       
        return $this;
    }
    
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);       
        return $this;
    }
    
    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);       
        return $this;
    }
}