<?php

/**
 * \Zion\Form\FormEscolha()
 * 
 * @author The Sappiens Team
 * @copyright Sappiens 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\Form;
use \Zion\Form\Exception\FormException as FormException;

class FormEscolha extends \Zion\Form\FormBasico
{
    private $tipoBase;
    private $acao;
    private $multiplo;
    private $expandido;
    private $ordena;
    private $array;
    private $inicio;
    private $tabela;
    private $campoCod;
    private $campoDesc;
    private $where;
    private $sqlCompleto;
    private $idConexao;
    private $placeHolder;
    private $label;
    private $emColunaDeTamanho;

    /**
     * FormEscolha::__construct()
     * 
     * @param mixed $acao
     * @return
     */
    public function __construct($acao)
    {
        $this->tipoBase = 'escolha';
        $this->acao = $acao;
        $this->expandido = false;
        $this->multiplo = false;
        $this->ordena = 'ASC';
        $this->inicio = 'Selecione...';
        $this->label = true;
    }

    /**
     * FormEscolha::getTipoBase()
     * 
     * @return
     */
    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    /**
     * FormEscolha::getAcao()
     * 
     * @return
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * FormEscolha::getMiltiplo()
     * 
     * @return
     */
    public function getMiltiplo()
    {
        return $this->multiplo;
    }

    /**
     * FormEscolha::setMultiplo()
     * 
     * @param mixed $multiplo
     * @return
     */
    public function setMultiplo($multiplo)
    {
        if(!is_null($multiplo)){
            $this->multiplo = $multiplo;
            return $this;
        } else {
            throw new FormException("multiplo: Valor nao booleano");
        }
    }
    
    /**
     * FormEscolha::getExpandido()
     * 
     * @return
     */
    public function getExpandido()
    {
        return $this->expandido;
    }

    /**
     * FormEscolha::setExpandido()
     * 
     * @param mixed $expandido
     * @return
     */
    public function setExpandido($expandido)
    {
        if(!is_null($expandido)){
            $this->expandido = $expandido;
            return $this;
        } else {
            throw new FormException("expandido: Valor nao booleano");
        }
    }
    
    /**
     * FormEscolha::getOrdena()
     * 
     * @return
     */
    public function getOrdena()
    {
        return $this->ordena;
    }

    /**
     * FormEscolha::setOrdena()
     * 
     * @param mixed $ordena
     * @return
     */
    public function setOrdena($ordena)
    {
        if(!is_null($ordena)){
            $this->ordena = $ordena;
            return $this;
        } else {
            throw new FormException("ordena: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getArray()
     * 
     * @return
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * FormEscolha::setArray()
     * 
     * @param mixed $array
     * @return
     */
    public function setArray($array)
    {
        if(is_array($array)){
            $this->array = $array;
            return $this;
        } else {
            throw new FormException("array: O valor informado nao e um array.");
        }
    }

    /**
     * FormEscolha::getInicio()
     * 
     * @return
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * FormEscolha::setInicio()
     * 
     * @param mixed $inicio
     * @return
     */
    public function setInicio($inicio)
    {
        if(!is_null($inicio)){
            $this->inicio = $inicio;
            return $this;
        } else {
            throw new FormException("inicio: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getTabela()
     * 
     * @return
     */
    public function getTabela()
    {
        return $this->tabela;
    }

    /**
     * FormEscolha::setTabela()
     * 
     * @param mixed $tabela
     * @return
     */
    public function setTabela($tabela)
    {
        if(!is_null($tabela)){
            $this->tabela = $tabela;
            return $this;
        } else {
            throw new FormException("tabela: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getCampoCod()
     * 
     * @return
     */
    public function getCampoCod()
    {
        return $this->campoCod;
    }

    /**
     * FormEscolha::setCampoCod()
     * 
     * @param mixed $campoCod
     * @return
     */
    public function setCampoCod($campoCod)
    {
        if(!is_null($campoCod)){
            $this->campoCod = $campoCod;
            return $this;
        } else {
            throw new FormException("campoCod: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getCampoDesc()
     * 
     * @return
     */
    public function getCampoDesc()
    {
        return $this->campoDesc;
    }

    /**
     * FormEscolha::setCampoDesc()
     * 
     * @param mixed $campoDesc
     * @return
     */
    public function setCampoDesc($campoDesc)
    {
        if(!is_null($campoDesc)){
            $this->campoDesc = $campoDesc;
            return $this;
        } else {
            throw new FormException("campoDesc: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getWhere()
     * 
     * @return
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * FormEscolha::setWhere()
     * 
     * @param mixed $where
     * @return
     */
    public function setWhere($where)
    {
        if(!is_null($where)){
            $this->where = $where;
            return $this;
        } else {
            throw new FormException("where: Nenhum Valor foi informado.");
        }
    }

    /**
     * FormEscolha::getSqlCompleto()
     * 
     * @return
     */
    public function getSqlCompleto()
    {
        return $this->sqlCompleto;
    }

    /**
     * FormEscolha::setSqlCompleto()
     * 
     * @param mixed $sqlCompleto
     * @return
     */
    public function setSqlCompleto($sqlCompleto)
    {
        if(!is_null($sqlCompleto)){
            $this->sqlCompleto = $sqlCompleto;
            return $this;
        } else {
            throw new FormException("sqlCompleto: Nenhum Valor foi informado.");
        }
    }
    
    /**
     * FormEscolha::getIdConexao()
     * 
     * @return
     */
    public function getIdConexao()
    {
        return $this->idConexao;
    }

    /**
     * FormEscolha::setIdConexao()
     * 
     * @param mixed $idConexao
     * @return
     */
    public function setIdConexao($idConexao)
    {
        if(!is_null($idConexao)){
            $this->idConexao = $idConexao;
            return $this;
        } else {
            throw new FormException("idConexao: Nenhum Valor foi informado.");
        }
    }
    
    /**
     * FormEscolha::setPlaceHolder()
     * 
     * @param mixed $placeHolder
     * @return
     */
    public function setPlaceHolder($placeHolder)
    {
        if (!empty($placeHolder)) {
            $this->placeHolder = $placeHolder;
            return $this;
        } else {
            throw new FormException("placeHolder: Nenhum valor informado");
        }
    }

    /**
     * FormEscolha::getPlaceHolder()
     * 
     * @return
     */
    public function getPlaceHolder()
    {
        return $this->placeHolder;
    }
    
    /**
     * FormEscolha::setLabel()
     * 
     * @param mixed $label
     * @return
     */
    public function setLabel($label)
    {
        if (is_bool($label)) {
            $this->label = $label;
            return $this;
        } else {
            throw new FormException("label: O valor informado não é um booleano.");
        }
    }

    /**
     * FormEscolha::getLabel()
     * 
     * @return
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * FormEscolha::setEmColunaDeTamanho()
     * 
     * @param mixed $emColunaDeTamanho
     * @return
     */
    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {        
        if (in_array($emColunaDeTamanho, range(1, 12))) {
            $this->emColunaDeTamanho = $emColunaDeTamanho;
            return $this;
        } else {
            throw new FormException("emColunaDeTamanho: Use variação de 1 a 12");
        }
    }

    /**
     * FormEscolha::getemColunaDeTamanho()
     * 
     * @return
     */
    public function getemColunaDeTamanho()
    {
        return $this->emColunaDeTamanho;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */
    /**
     * FormEscolha::setId()
     * 
     * @param mixed $id
     * @return
     */
    public function setId($id)
    {
        parent::setId($id);        
        return $this;
    }
    
    /**
     * FormEscolha::setNome()
     * 
     * @param mixed $nome
     * @return
     */
    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }
    
    /**
     * FormEscolha::setIdentifica()
     * 
     * @param mixed $identifica
     * @return
     */
    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }
    
    /**
     * FormEscolha::setValor()
     * 
     * @param mixed $valor
     * @return
     */
    public function setValor($valor)
    {              
        parent::setValor($valor);
        return $this;
    }
    
    /**
     * FormEscolha::setValorPadrao()
     * 
     * @param mixed $valorPadrao
     * @return
     */
    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }
    
    /**
     * FormEscolha::setDisabled()
     * 
     * @param mixed $disabled
     * @return
     */
    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }
    
    /**
     * FormEscolha::setComplemento()
     * 
     * @param mixed $complemento
     * @return
     */
    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    /**
     * FormEscolha::setAtributos()
     * 
     * @param mixed $atributos
     * @return
     */
    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }
    
    /**
     * FormEscolha::setClassCss()
     * 
     * @param mixed $classCss
     * @return
     */
    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);
        return $this;
    }
}
