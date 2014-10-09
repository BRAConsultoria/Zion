<?php

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;

class FormInputTexto extends FormBasico
{

    protected $tipoBase;
    protected $acao;
    protected $largura;
    protected $maximoCaracteres;
    protected $minimoCaracteres;
    protected $valorMaximo;
    protected $valorMinimo;
    protected $caixa;
    protected $mascara;
    protected $obrigatorio;
    protected $converterHtml;
    protected $autoTrim;
    protected $placeHolder;
    protected $autoComplete;
    protected $deveSerIgualA;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'texto';        
        $this->acao = $acao;
        $this->autoTrim = true;
        $this->converterHtml = true;
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

    public function setMaximoCaracteres($maximoCaracteres)
    {
        if (is_numeric($maximoCaracteres)) {

            if (isset($this->minimoCaracteres) and ( $maximoCaracteres < $this->minimoCaracteres)) {
                throw new FormException("maximoCaracteres nao pode ser menor que minimoCaracteres.");
                return;
            }

            $this->maximoCaracteres = $maximoCaracteres;
            return $this;
        } else {
            throw new FormException("maximoCaracteres: Valor nao numerico.");
        }
    }

    public function getMaximoCaracteres()
    {
        return $this->maximoCaracteres;
    }

    public function setMinimoCaracteres($minimoCaracteres)
    {
        if (is_numeric($minimoCaracteres)) {

            if (isset($this->maximoCaracteres) and ( $minimoCaracteres > $this->maximoCaracteres)) {
                throw new FormException("minimoCaracteres nao pode ser maior que maximoCaracteres.");
                return;
            }

            $this->minimoCaracteres = $minimoCaracteres;
            return $this;
        } else {
            throw new FormException("minimoCaracteres: Valor nao numerico.");
        }
    }

    public function getMinimoCaracteres()
    {
        return $this->minimoCaracteres;
    }

    public function setCaixa($caixa)
    {
        if (strtoupper($caixa) == "ALTA" or strtoupper($caixa) == "BAIXA") {
            $this->caixa = $caixa;
            return $this;
        } else {
            throw new FormException("caixa: Valor desconhecido: " . $caixa);
        }
    }

    public function getCaixa()
    {
        return $this->caixa;
    }

    public function setValorMinimo($valorMinimo)
    {
        if (is_numeric($valorMinimo)) {

            if (isset($this->valorMaximo) and ( $valorMinimo > $this->valorMaximo)) {
                throw new FormException("valorMinimo nao pode ser maior que valorMaximo.");
                return;
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
        if (is_numeric($valorMaximo)) {

            if (isset($this->valorMinimo) and ( $valorMaximo < $this->valorMinimo)) {
                throw new FormException("valorMaximo nao pode ser menor que valorMinimo.");
                return;
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

    public function setMascara($mascara)
    {
        if (!empty($mascara)) {
            $this->mascara = $mascara;
            return $this;
        } else {
            throw new FormException("mascara: Nenhum valor informado");
        }
    }

    public function getMascara()
    {
        return $this->mascara;
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

    public function setConverterHtml($converterHtml)
    {
        if (is_bool($converterHtml)) {
            $this->converterHtml = $converterHtml;
            return $this;
        } else {
            throw new FormException("converterHtml: Valor nao booleano");
        }
    }

    public function getConverterHtml()
    {
        return $this->converterHtml;
    }

    public function setAutoTrim($autoTrim)
    {
        if (is_bool($autoTrim)) {
            $this->autoTrim = $autoTrim;
            return $this;
        } else {
            throw new FormException("autoTrim: Valor nao booleano");
        }
    }

    public function getAutoTrim()
    {
        return $this->autoTrim;
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

    public function setAutoComplete($autoComplete)
    {
        if (is_bool($autoComplete)) {
            $this->autoComplete = $autoComplete;
            return $this;
        } else {
            throw new FormException("autoComplete: O valor informado não é um booleano.");
        }
    }

    public function getAutoComplete()
    {
        return $this->autoComplete;
    }

    public function setDeveSerIgualA($deveSerIgualA)
    {
        if (!empty($deveSerIgualA)) {
            $this->deveSerIgualA = $deveSerIgualA;
            return $this;
        } else {
            throw new FormException("deveSerIgualA: O valor informado não é um valor válido.");
        }
    }

    public function getDeveSerIgualA()
    {
        return $this->deveSerIgualA;
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
