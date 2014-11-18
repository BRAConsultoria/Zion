<?php

/**
 * \Zion\Form\FormAtributos()
 * 
 * @author The Sappiens Team
 * @copyright Sappiens 2014
 * @version 2014
 * @access public
 */

namespace Zion\Form;

class FormAtributos
{

    private $atributos;

    /**
     * FormAtributos::__construct()
     * 
     * @return
     */
    public function __construct()
    {
        $this->atributos = [
            'name' => ' name="%s" ',
            'id' => ' id="%s" ',
            'type' => ' type="%s" ',
            'value' => ' value="%s" ',
            'size' => ' size="%s" ',
            'maxlength' => ' maxlength="%s" ',
            'disabled' => ' disabled="%s" ',
            'placeholder' => ' placeholder="%s" ',
            'autocomplete' => ' autocomplete="%s" ',
            'caixa' => ' style="text-transform:%s;" ',
            'max' => ' max="%s" ',
            'min' => ' min="%s" ',
            'classCss' => 'class="%s"',
            'option' => '<option value="%s">%s</option>',
            'formmethod' => ' formmethod="%s" ',
            'formaction' => ' formaction="%s" ',
            'formtarget' => ' formtarget="%s" ',
            'complemento' => ' %s ',
            'valueButton' => ' %s ',
            'valueTextArea' => ' %s ',
            'readonly' => ' %s ',
            'multiple' => ' %s ',
            'colunas' => ' cols="%s" ',
            'linhas' => ' rows="%s" ',
            'action' => ' action="%s" ',
            'enctype' => ' enctype="%s" ',
            'method' => ' method="%s" ',
            'novalidate' => ' novalidate="%s" ',
            'target' => ' target="%s" '
        ];
    }

    /**
     * FormAtributos::tipoEspecial()
     * 
     * @param mixed $tipo
     * @param mixed $valor
     * @return
     */
    private function tipoEspecial($tipo, $valor)
    {
        if ($valor == '' and $valor !== false) {
            return '';
        }

        switch ($tipo) {
            case 'disabled':
                $ret = 'disabled';
                break;
            case 'multiple':
                $ret = 'multiple';
                break;
            case 'caixa':
                $ret = $valor == 'ALTA' ? 'uppercase' : 'lowercase';
                break;
            case 'autocomplete':
                $ret = $valor === true ? 'on' : 'off';
                break;
            default : $ret = $valor;
        }

        return $ret;
    }

    /**
     * FormAtributos::attr()
     * 
     * @param mixed $nome
     * @return
     */
    protected function attr($nome)
    {
        $pars = array();

        $args = func_get_args();

        $cont = 0;
        foreach ($args as $valor) {

            $valor = $this->tipoEspecial($nome, $valor);

            $cont++;
            if ($cont == 1 or ( $nome != 'value' and $nome != 'id' and $valor == '')) {
                continue;
            }

            $pars[] = $valor;
        }

        return $pars ? vsprintf($this->atributos[$nome], $pars) : '';
    }

    /**
     * FormAtributos::prepareButton()
     * 
     * @param mixed $totalAtributos
     * @return
     */
    protected function prepareButton($totalAtributos, $config)
    {
        $buffer = '';

        if ($config->getContainer()) {
            $buffer .= '<div id="' . $config->getContainer() . '">';
        }

        $buffer .= "<button " . str_repeat('%s', $totalAtributos - 1) . ">%s</button>";

        if ($config->getContainer()) {
            $buffer .= '</div>';
        }

        return $buffer;
    }

    /**
     * FormAtributos::prepareForm()
     * 
     * @param mixed $totalAtributos
     * @return
     */
    protected function prepareForm($totalAtributos)
    {
        return "<form " . str_repeat('%s', $totalAtributos) . ">";
    }

    /**
     * FormAtributos::prepareInput()
     * 
     * @param mixed $totalAtributos
     * @return
     */
    protected function prepareInput($totalAtributos, $config)
    {
        $buffer = '';

        if ($config->getContainer()) {
            $buffer .= '<div id="' . $config->getContainer() . '">';
        }

        $buffer .= '<input ' . str_repeat('%s', $totalAtributos) . '/>';

        if ($config->getContainer()) {
            $buffer .= '</div>';
        }

        return $buffer;
    }

    protected function prepareTextArea($totalAtributos, $config)
    {
        $buffer = '';

        if ($config->getContainer()) {
            $buffer .= '<div id="' . $config->getContainer() . '">';
        }

        $buffer .= "<textarea " . str_repeat('%s', $totalAtributos - 1) . ">%s</textarea>";

        if ($config->getContainer()) {
            $buffer .= '</div>';
        }

        return $buffer;
    }

}
