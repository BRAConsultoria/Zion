<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 *
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

namespace Zion\Form;

use \Zion\Form\Exception\FormException as FormException;
use Zion\Form\FormBasico;


class FormEscolha extends FormBasico implements FilterableInput
{

    private $tipoBase;
    private $acao;
    private $obrigatorio;
    private $selecaoMaxima;
    private $selecaoMinima;
    private $multiplo;
    private $expandido;
    private $chosen;
    private $ordena;
    private $array;
    private $inicio;
    private $tabela;
    private $campoCod;
    private $campoDesc;
    private $instrucoes;
    private $orderBy;
    private $sqlCompleto;
    private $idConexao;
    private $aliasSql;
    private $ignoreCod;
    private $callback;
    private $naoSelecionaveis;
    private $categoriaFiltro;
    private $filtroPadrao;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        $this->tipoBase = 'escolha';
        $this->acao = $acao;
        $this->setNome($nome);
        $this->setId($nome);
        $this->setIdentifica($identifica);
        $this->setObrigarorio($obrigatorio);
        $this->expandido = false;
        $this->multiplo = false;
        $this->chosen = false;
        $this->ordena = 'ASC';
        $this->inicio = 'Selecione...';
        $this->instrucoes = [];
        $this->categoriaFiltro = FilterableInput::EQUAL;
    }

    public function getTipoBase()
    {
        return $this->tipoBase;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function setObrigarorio($obrigatorio)
    {
        if (is_bool($obrigatorio)) {
            $this->obrigatorio = $obrigatorio;
            return $this;
        } else {
            throw new FormException("obrigatorio: Valor não booleano");
        }
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    public function setSelecaoMaxima($selecaoMaxima)
    {
        if (is_numeric($selecaoMaxima)) {
            if (isset($this->selecaoMinima) and $selecaoMaxima < $this->selecaoMinima) {
                throw new FormException("selecaoMaxima não pode ser menor que selecao mínima.");
            }
            $this->selecaoMaxima = $selecaoMaxima;
            return $this;
        } else {
            throw new FormException("selecaoMaxima: O valor informado deve ser do tipo numérico.");
        }
    }

    public function getSelecaoMaxima()
    {
        return $this->selecaoMaxima;
    }

    public function setSelecaoMinima($selecaoMinima)
    {
        if (is_numeric($selecaoMinima)) {
            if (isset($this->selecaoMaxima) and $selecaoMinima > $this->selecaoMaxima) {
                throw new FormException("selecaoMinima não pode ser maior que seleção máxima.");
            }
            $this->selecaoMinima = $selecaoMinima;
            return $this;
        } else {
            throw new FormException("selecaoMinima: O valor informado deve ser do tipo numérico.");
        }
    }

    public function getSelecaoMinima()
    {
        return $this->selecaoMinima;
    }

    public function getMultiplo()
    {
        return $this->multiplo;
    }

    public function setMultiplo($multiplo)
    {
        if (!is_null($multiplo)) {
            $this->multiplo = $multiplo;
            return $this;
        } else {
            throw new FormException("multiplo: Valor não booleano");
        }
    }

    public function getExpandido()
    {
        return $this->expandido;
    }

    public function setExpandido($expandido)
    {
        if (!is_null($expandido)) {
            $this->expandido = $expandido;
            return $this;
        } else {
            throw new FormException("expandido: Valor não booleano");
        }
    }

    protected function setChosen($chosen)
    {
        $this->chosen = $chosen;
    }

    public function getChosen()
    {
        return $this->chosen;
    }

    public function getOrdena()
    {
        return $this->ordena;
    }

    public function setOrdena($ordena)
    {
        if (!is_null($ordena)) {
            $this->ordena = $ordena;
            return $this;
        } else {
            throw new FormException("ordena: Nenhum Valor foi informado.");
        }
    }

    public function getArray()
    {
        return $this->array;
    }

    public function setArray($array)
    {
        if (\is_array($array)) {
            $this->array = $array;
            return $this;
        } else {
            throw new FormException("array: O valor informado não é um array.");
        }
    }

    public function getInicio()
    {
        return $this->inicio;
    }

    public function setInicio($inicio)
    {
        if (!is_null($inicio)) {
            $this->inicio = $inicio;
            return $this;
        } else {
            throw new FormException("inicio: Nenhum Valor foi informado.");
        }
    }

    public function getTabela()
    {
        return $this->tabela;
    }

    public function setTabela($tabela)
    {
        if (!is_null($tabela)) {
            $this->tabela = $tabela;
            return $this;
        } else {
            throw new FormException("tabela: Nenhum Valor foi informado.");
        }
    }

    public function getCampoCod()
    {
        return $this->campoCod;
    }

    public function setCampoCod($campoCod)
    {
        if (!\is_null($campoCod)) {
            $this->campoCod = \strtolower($campoCod);
            return $this;
        } else {
            throw new FormException("campoCod: Nenhum Valor foi informado.");
        }
    }

    public function getCampoDesc()
    {
        return $this->campoDesc;
    }

    public function setCampoDesc($campoDesc)
    {
        if (!is_null($campoDesc)) {
            $this->campoDesc = \strtolower($campoDesc);
            return $this;
        } else {
            throw new FormException("campoDesc: Nenhum Valor foi informado.");
        }
    }

    public function getInstrucoes()
    {
        return $this->instrucoes;
    }

    public function setInstrucoes(array $instrucoes)
    {
        if (!empty($instrucoes)) {
            $this->instrucoes[] = $instrucoes;
        }

        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy)
    {
        if (!empty($orderBy)) {
            if (\is_array($orderBy)) {
                $this->orderBy[] = $orderBy;
                return $this;
            } else {
                throw new FormException("setOrderBy: Se informado o valor deve ser um array.");
            }
        }
    }

    public function getSqlCompleto()
    {
        return $this->sqlCompleto;
    }

    public function setSqlCompleto($sqlCompleto)
    {
        if (!is_null($sqlCompleto)) {
            $this->sqlCompleto = $sqlCompleto;
            return $this;
        } else {
            throw new FormException("sqlCompleto: Nenhum Valor foi informado.");
        }
    }

    public function getIdConexao()
    {
        return $this->idConexao;
    }

    public function setIdConexao($idConexao)
    {
        if (!is_null($idConexao)) {
            $this->idConexao = $idConexao;
            return $this;
        } else {
            throw new FormException("idConexao: Nenhum Valor foi informado.");
        }
    }

    public function getAliasSql()
    {
        return $this->aliasSql;
    }

    public function setAliasSql($aliasSql)
    {
        if (!is_null($aliasSql)) {
            $this->aliasSql = $aliasSql;
            return $this;
        } else {
            throw new FormException("aliasSql: Nenhum valor informado");
        }
    }

    public function setIgnoreCod($ignoreCod)
    {
        if (is_array($ignoreCod)) {
            $this->ignoreCod = $ignoreCod;
            return $this;
        } else {
            throw new FormException("ignoreCod: O valor informado não é um array.");
        }
    }

    public function getIgnoreCod()
    {
        return $this->ignoreCod;
    }

    public function setCallback($callback)
    {
        if (\is_string($callback)) {
            $this->callback = $callback;
            return $this;
        } else {
            throw new FormException("callback: O valor informado não é uma string.");
        }
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setNaoSelecionaveis($naoSelecionaveis)
    {
        if (\is_null($naoSelecionaveis) or \is_array($naoSelecionaveis)) {
            $this->naoSelecionaveis = $naoSelecionaveis;
            return $this;
        } else {
            throw new FormException("naoSelecionaveis: O valor informado não é um null ou array.");
        }
    }

    public function getNaoSelecionaveis()
    {
        return empty($this->naoSelecionaveis) ? [] : $this->naoSelecionaveis;
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

    public function setContainer($container)
    {
        parent::setContainer($container);
        return $this;
    }

    public function setCategoriaFiltro($categoria)
    {
        $this->categoriaFiltro = $categoria;

        return $this;
    }

    public function getCategoriaFiltro()
    {
        return $this->categoriaFiltro;
    }
    
        public function setFiltroPadrao($filtroPadrao)
    {
        $this->filtroPadrao = $filtroPadrao;

        return $this;
    }

    public function getFiltroPadrao()
    {
        return $this->filtroPadrao;
    }
}
