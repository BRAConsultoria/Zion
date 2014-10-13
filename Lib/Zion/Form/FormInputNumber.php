<?php

namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;

class FormInputNumber extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $largura;
    private $valorMaximo;
    private $valorMinimo;
    private $obrigatorio;
    private $placeHolder;
    
    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'number';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
    }
    
    public function getTipoBase()
    {
        return $this->tipoBase;
    }
    
    public function getAcao()
    {
        return $this->acao;
    }
    
    public function setLargura($largura)
    {
        if (preg_match('/^[0-9]{1,}[%]{1}$|^[0-9]{1,}[px]{2}$|^[0-9]{1,}$/', $largura)) {
            $this->largura = $largura;
            return $this;
        } else {
            throw new FormException("largura: O valor nao esta nos formatos aceitos: 10%; 10px; ou 10");
        }
    }

    public function getLargura()
    {
        return $this->largura;
    }
    
    public function setValorMinimo($valorMinimo)
    {
        if(is_numeric($valorMinimo)){

            if(isset($this->valorMaximo) and ($valorMinimo > $this->valorMaximo)) {
                throw new FormException("valorMinimo nao pode ser maior que valorMaximo.");
            }

            $this->valorMinimo = $valorMinimo;
            return $this;
        } else {
            throw new FormException("valorMinimo: Valor nao numerico");
        }
    }
    
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }
    
    public function setValorMaximo($valorMaximo)
    {
        if(is_numeric($valorMaximo)){

            if(isset($this->valorMinimo) and ($valorMaximo < $this->valorMinimo)) {
                throw new FormException("valorMaximo nao pode ser menor que valorMinimo.");
            }

            $this->valorMaximo = $valorMaximo;
            return $this;
        } else {
            throw new FormException("valorMaximo: Valor nao numerico");
        }
    }
    
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor nao booleano");
        }
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }
    
    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }
    }

    public function getPlaceHolder()
    {
        return $this->placeHolder;
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