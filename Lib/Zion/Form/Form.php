<?php

namespace Zion\Form;

class Form extends \Zion\Form\FormHtml
{

    public $formConfig;
    private $formValues;
    private $processarHtml;
    private $processarJs;
    private $formHtml;

    public function __construct()
    {
        parent::__construct();
        
        $this->formConfig = new \Zion\Form\FormTag();

        $this->formConfig->setNome('Form1')
                ->setMethod('POST');

        $this->formValues = array();
        $this->processarHtml = true;
        $this->processarJs = true;
        $this->formHtml = array();
    }

    public function hidden()
    {
        return new \Zion\Form\FormInputHidden('hidden');
    }

    public function texto()
    {
        return new \Zion\Form\FormInputTexto('texto');
    }

    public function suggest()
    {
        return new \Zion\Form\FormInputSuggest('suggest');
    }

    public function data()
    {
        return new \Zion\Form\FormInputDateTime('date');
    }

    public function hora()
    {
        return new \Zion\Form\FormInputDateTime('time');
    }

    public function senha()
    {
        return new \Zion\Form\FormInputTexto('password');
    }

    public function numero()
    {
        return new \Zion\Form\FormInputNumber('number');
    }

    public function float()
    {
        return new \Zion\Form\FormInputTexto('moeda');
    }

    public function cpf()
    {
        return new \Zion\Form\FormInputTexto('cpf');
    }

    public function cnpj()
    {
        return new \Zion\Form\FormInputTexto('cnpj');
    }

    public function cep()
    {
        return new \Zion\Form\FormInputTexto('cep');
    }

    public function telefone()
    {
        return new \Zion\Form\FormInputTexto('telefone');
    }

    public function email()
    {
        return new \Zion\Form\FormInputTexto('email');
    }

    public function escolha()
    {
        return new \Zion\Form\FormEscolha('escolha');
    }

    public function textArea()
    {
        return new \Zion\Form\FormInputTexto('email');
    }

    public function editor()
    {
        return new \Zion\Form\FormInputTexto('email');
    }

    public function upload()
    {
        return new \Zion\Form\FormInputTexto('email');
    }

    public function botaoSubmit()
    {
        return new \Zion\Form\FormInputButton('bubmit');
    }

    public function botaoSimples()
    {
        return new \Zion\Form\FormInputButton('button');
    }

    public function botaoReset()
    {
        return new \Zion\Form\FormInputButton('reset');
    }

    /** 
    * @return FormTag 
    */ 
    public function config()
    {
        return $this->formConfig;
    }

    public function abreForm()
    {
        return parent::abreForm($this->formConfig);
    }
    
    public function fechaForm()
    {
        return parent::fechaForm();
    }

    public function processarForm(array $campos)
    {
        $htmlCampos = array();

        foreach ($campos as $objCampos) {

            if ($this->processarHtml) {
                switch ($objCampos->getTipoBase()) {
                    case 'hidden' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaHidden($objCampos);
                        break;
                    case 'texto' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaTexto($objCampos);
                        break;
                    case 'suggest' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaSuggest($objCampos);
                        break;
                    case 'dateTime' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaDateTime($objCampos);
                        break;
                    case 'number' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaNumber($objCampos);
                        break;
                    case 'float' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaFloat($objCampos);
                        break;
                    case 'cpf' :
                        $htmlCampos[$objCampos->getNome()] = $this->montaTexto($objCampos);
                        break;
                    case 'escolha':
                        $htmlCampos[$objCampos->getNome()] = $this->montaEscolha($objCampos);
                        break;
                    case 'button':
                        $htmlCampos[$objCampos->getNome()] = $this->montaButton($objCampos);
                        break;
                }
            }

            $this->formValues[$objCampos->getNome()] = $objCampos->getValor();
        }

        if ($this->processarHtml) {
            $this->formHtml = $htmlCampos;
        }

        return $this;
    }

    public function retornaValor($nome)
    {
        switch ($this->formConfig->getMethod()) {
            case "POST" : $valor = @$_POST[$nome];
                break;
            case "GET" : $valor = @$_GET[$nome];
                break;
            default: $valor = null;
        }

        return $valor;
    }

    public function set($nome, $valor)
    {
        if(!is_null($nome) or !is_null($nome)){
            $this->formValues[$nome] = $valor;
        } else {
            throw new FormException("set: Falta um argumento.");
        }
    }

    public function get($nome)
    {
        return $this->formValues[$nome];
    }

    public function setProcessarHtml($processarHtml)
    {
        if(is_bool($processarHtml)){
            $this->processarHtml = $processarHtml;
        } else {
            throw new FormException("processarHtml: O valor informado nao e um booleano.");
        }
    }

    public function setProcessarJs($processarJs)
    {
        if(is_bool($processarJs)){
            $this->processarJs = $processarJs;
        } else {
            throw new FormException("processarJs: O valor informado nao e um booleano.");
        }        
    }

    public function getFormHtml($nome = null)
    {
        return $nome ? $this->formHtml[$nome] : $this->formHtml;
    }

    public function setNomeForm($nome)
    {
        if(!is_null($nome)){
            $this->nomeForm = $nome;
        } else {
            throw new FormException("nome: Nenhum valor informado.");
        }        
    }

    public function getNomeForm()
    {
        return $this->nomeForm;
    }

}
