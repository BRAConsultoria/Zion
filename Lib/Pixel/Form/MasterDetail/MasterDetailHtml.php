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

namespace Pixel\Form\MasterDetail;

use Pixel\Form\MasterDetail\FormMasterDetail;
use Zion\Banco\Conexao;
use Pixel\Form\Form;
use Pixel\Form\FormPixelJavaScript;
use Pixel\Twig\Carregador;
use App\Sistema\Ajuda\AjudaView;

class MasterDetailHtml {

    private $buffer;

    public function __construct() {
        $this->buffer = [];

        $this->buffer['ativos'] = '';
    }

    public function montaMasterDetail(FormMasterDetail $config, $nomeForm) {
        $totalInicio = $config->getTotalItensInicio();
        $objPai = $config->getObjetoPai();
        $valorItensDeInicio = $config->getValorItensDeInicio();
        $parametrosView = $config->getParametrosView();

        $this->buffer['id'] = $config->getNome();

        $acao = $objPai->getAcao();

        if ($acao == 'alterar') {

            $this->camposDoBanco($config, $nomeForm);
        } else {
            for ($i = 0; $i < $totalInicio; $i++) {

                $coringa = $this->coringa();

                $valoresInicio = [];
                if (\is_array($valorItensDeInicio[$i])) {
                    $valoresInicio = \array_change_key_case($valorItensDeInicio[$i]);

                    if (\array_key_exists('sisCoringa', $valorItensDeInicio[$i])) {
                        $coringa = $valorItensDeInicio[$i]['sisCoringa'];
                    }
                }

                $this->montaGrupoDeCampos($config, $coringa, $nomeForm, $valoresInicio);
            }
        }

        $this->botaoAdd($config, $nomeForm, $this->buffer['ativos']);

        if ($config->getBotaoRemover()) {
            $this->buffer['botaoRemover'] = 'true';
        }

        if ($config->getBotaoAdd()) {
            $this->buffer['mostrarBotaoAdd'] = 'true';
        }

        if ($parametrosView) {
            foreach ($parametrosView as $pvChave => $pvValue) {
                $this->buffer[$pvChave] = $pvValue;
            }
        }

        $carregador = new Carregador($config->getNamespace());

        return $carregador->render($config->getView(), $this->buffer);
    }

    private function camposDoBanco(FormMasterDetail $config, $nomeForm) {
        $con = Conexao::conectar();

        $ativos = [];

        $sqlBusca = $config->getSqlBusca();

        $tabela = $config->getTabela();
        $codigo = $config->getCodigo();
        $campoReferencia = $config->getCampoReferencia();
        $codigoReferencia = $config->getCodigoReferencia();
        $campos = $config->getCampos();
        $nomeCampos = \array_keys($campos);

        foreach ($nomeCampos as $chave => $nome) {

            if (\substr_count($nome, '[]') > 0) {
                $nomeCampos[$chave] = \str_replace('[]', '', $nome);
            }

            if ($campos[$nome]->getTipoBase() === 'upload') {
                unset($nomeCampos[$chave]);
            }
        }

        if (!\in_array($codigo, $nomeCampos)) {

            $nomeCampos[] = $codigo;
        }

        if ($sqlBusca) {
            $qb = $sqlBusca;
        } else {
            $qb = $con->qb();
            $qb->select(\implode(',', $nomeCampos))
                    ->from($tabela, '')
                    ->where($qb->expr()->eq($campoReferencia, ':cod'))
                    ->setParameter(':cod', $codigoReferencia);
        }

        $rs = $con->executar($qb);

        while ($dados = $rs->fetch()) {

            $coringa = $dados[$codigo];
            $ativos[] = $coringa;
            $this->montaGrupoDeCampos($config, $coringa, $nomeForm, $dados);
        }

        $this->buffer['ativos'] = \implode(',', $ativos);
    }

    private function montaGrupoDeCampos($config, $coringa, $nomeForm, array $valores = [], $limpar = false) {
        $form = new Form();
        $pixelJs = new FormPixelJavaScript();

        $campos = $config->getCampos();

        $nomeOriginal = '';
        $ajudaViewClass = null;

        foreach ($campos as $nomeOriginal => $configuracao) {

            $arCampos = [];

            $temColchetes = false;

            if (\substr_count($nomeOriginal, '[]') > 0) {
                $temColchetes = true;
                $nomeOriginal = \str_replace('[]', '', $nomeOriginal);
            }

            $novoNomeId = $nomeOriginal . $coringa . ($temColchetes ? '[]' : '');
            $nomeOriginalMinusculo = \strtolower($nomeOriginal);

            if (!empty($valores) and \array_key_exists($nomeOriginalMinusculo, $valores)) {

                if ($temColchetes) {
                    $v = \explode(',', $valores[$nomeOriginalMinusculo]);
                } else {
                    $v = $valores[$nomeOriginalMinusculo];
                }

                $configuracao->setValor($v);
            }

            if ($limpar) {
                $valorPadrao = $configuracao->getValorPadrao();
                if ($valorPadrao) {
                    $configuracao->setValor($valorPadrao);
                } else {
                    $configuracao->setValor(\NULL);
                }
            }

            //upload
            if ($configuracao->getTipoBase() === 'upload' and \is_numeric($coringa)) {
                $configuracao->setCodigoReferencia($coringa);
            }

            $arCampos[] = $configuracao->setNome($novoNomeId)->setId($novoNomeId);

            if ($configuracao->getTipoBase() === 'upload') {
                //echo $configuracao->getNome() . '-' . $novoNomeId . '-' . $coringa . ' - ' . $configuracao->getTipoBase() . "\n\n\n";
            }

            $form->processarForm($arCampos);

            $this->buffer['campos'][$coringa][$nomeOriginal] = $form->getFormHtml($arCampos[0]);

            //upload
            if ($configuracao->getTipoBase() === 'upload' and \is_numeric($coringa)) {
                $configuracao->setCodigoReferencia(\NULL);
            }

            $this->buffer['tipos'][$nomeOriginal] = $configuracao->getTipoBase();

            if (\method_exists($configuracao, 'getHashAjuda') and $configuracao->getHashAjuda()) {

                try {
                    $ajudaViewClass = (\is_object($ajudaViewClass)) ? $ajudaViewClass : new AjudaView();

                    $this->buffer['ajudaHash'][$nomeOriginal] = $ajudaViewClass->getAjudaHash($configuracao->getHashAjuda());
                } catch (\Exception $e) {
                    // noop
                }
            }


            if (\method_exists($configuracao, 'getEmColunaDeTamanho')) {
                $this->buffer['emColunas'][$nomeOriginal] = $configuracao->getEmColunaDeTamanho();

                $offsetColunaA = $configuracao->getOffsetColuna();
                $this->buffer['offsetColunaA'][$nomeOriginal] = $offsetColunaA;
                $this->buffer['offsetColunaB'][$nomeOriginal] = (12 - $offsetColunaA);
            }

            if (\method_exists($configuracao, 'getIdentifica')) {
                $this->buffer['identifica'][$nomeOriginal] = $configuracao->getIdentifica();
            }

            if (\method_exists($configuracao, 'getLabelAntes') and $configuracao->getLabelAntes()) {
                $this->buffer['labelAntes'][$nomeOriginal] = $configuracao->getLabelAntes();
            }

            if (\method_exists($configuracao, 'getLabelAntes') and $configuracao->getLabelDepois()) {
                $this->buffer['labelAntes'][$nomeOriginal] = $configuracao->getLabelDepois();
            }

            if (\method_exists($configuracao, 'getIconFA') and $configuracao->getIconFA()) {
                $this->buffer['iconFA'][$nomeOriginal] = 'fa ' . $configuracao->getIconFA() . ' form-control-feedback';
            }

            if (\method_exists($configuracao, 'getComplementoExterno') and $configuracao->getComplementoExterno()) {
                $this->buffer['complementoExterno'][$nomeOriginal] = $configuracao->getComplementoExterno();
            }

            $js = $pixelJs->getJsExtraObjeto($arCampos, $nomeForm);

            if ($js) {
                $this->buffer['javascript'][$coringa] = $js;
            }
        }
    }

    private function botaoAdd(FormMasterDetail $config, $nomeForm, $ativos) {
        $coringa = $this->coringa();

        $this->buffer['botaoAdd'] = $config->getAddTexto();

        $this->montaGrupoDeCampos($config, $coringa, $nomeForm, [], true);

        $this->buffer['config'] = ['addMax' => $config->getAddMax(), 'addMin' => $config->getAddMin(), 'botaoRemover' => $config->getBotaoRemover() ? 'true' : 'false', 'coringa' => $coringa, 'ativos' => $ativos];
    }

    private function coringa() {
        $letras = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return \substr(\str_shuffle($letras), 0, 5);
    }

}
