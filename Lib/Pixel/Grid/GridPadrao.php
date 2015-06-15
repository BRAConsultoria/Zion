<?php/** * *    Sappiens Framework *    Copyright (C) 2014, BRA Consultoria * *    Website do autor: www.braconsultoria.com.br/sappiens *    Email do autor: sappiens@braconsultoria.com.br * *    Website do projeto, equipe e documentação: www.sappiens.com.br *    *    Este programa é software livre; você pode redistribuí-lo e/ou *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme *    publicada pela Free Software Foundation, versão 2. * *    Este programa é distribuído na expectativa de ser útil, mas SEM *    QUALQUER GARANTIA; sem mesmo a garantia implícita de *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais *    detalhes. *  *    Você deve ter recebido uma cópia da Licença Pública Geral GNU *    junto com este programa; se não, escreva para a Free Software *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA *    02111-1307, USA. * *    Cópias da licença disponíveis em /Sappiens/_doc/licenca * */namespace Pixel\Grid;use Zion\Banco\Conexao;use Zion\Paginacao\Paginacao;use Zion\Validacao\Valida;use Zion\Paginacao\Parametros;use Zion\Log\Log;class GridPadrao{    private $con;    private $paginacao;    private $botoes;    private $modoImpressao;    private $grid;    private $log;    /**     * @param \Zion\Banco\Conexao $con     */    public function __construct(Conexao $con = NULL)    {        $this->grid = new Grid();        $this->con = (!\is_object($con)) ? Conexao::conectar() : $this->con = $con;        $this->paginacao = new Paginacao($con);        $this->botoes = new GridBotoes();        $this->grid->setSelecaoMultipla(true);        //Padrões Iniciais        $this->grid->setTipoOrdenacao(\filter_input(\INPUT_GET, 'to'));        $this->grid->setQuemOrdena(\filter_input(\INPUT_GET, 'qo'));        $this->grid->setPaginaAtual(\filter_input(\INPUT_GET, 'pa'));        $this->grid->setQLinhas(\SIS_LINHAS_GRID);    }    private function tituloGridPadrao()    {        $buffer = [];        $colunas = $this->grid->getColunas();        $selecao = $this->grid->getSelecao();        $buffer['selecao'] = $selecao;        //Titulos        foreach ($colunas as $coluna => $titulo) {            $alinhamento = $this->grid->getAlinhamento($coluna);            if ($alinhamento) {                $buffer['alinhamento'][$coluna] = $this->grid->getAlinhamento($coluna);            }            $pr = Parametros::$parametros;            $buffer['titulo'][$coluna] = $this->grid->ordena($titulo, $coluna);            Parametros::limpaParametros();            Parametros::$parametros = $pr;        }        return $buffer;    }        public function montaGridPadrao()    {        //Modo de impresssão        $this->modoImpressao = false;        $colunasSemHTML = [];        if (\filter_input(\INPUT_GET, 'sisModoImpressao')) {            $this->modoImpressao = true;            $this->grid->setQLinhas(0);            $this->setSelecao(false);            $colunasSemHTML = $this->grid->getColunasSemHTML();        }        $buffer = [];        $bufferTitulo = $this->tituloGridPadrao();        //Recupera Valores        $sql = $this->grid->getSql();        $tabelaMestra = $this->grid->getTabelaMestra();        $sqlContador = $this->grid->getSqlContador();        $filtroAtivo = $this->grid->getFiltroAtivo();        $limitAtivo = $this->grid->getLimitAtivo();        $listados = \array_keys($this->grid->getColunas());        $chave = $this->grid->getChave();        $aliasOrdena = $this->grid->getAliasOrdena();        $formatarComo = $this->grid->getFormatarComo();        $selecao = $this->grid->getSelecao();        $selecaoMultipla = $this->grid->getSelecaoMultipla();        $qLinhas = $this->grid->getQLinhas();        $this->paginacao->setSql($sql);        //Verifica se o SQL não esta Vazio        if (empty($sql)) {            throw new \Exception("Valor selecionado inválido!");        }        if ($this->getLog() === true) {            (new Log())->registraLogUsuario($_SESSION['usuarioCod'], MODULO, 'filtrar', $this->grid->getSql());        }        //Se Formatações existem, intancie funções de validação.        if (!empty($formatarComo)) {            $fPHP = Valida::instancia();        }        $buffer['chave'] = $chave;        $buffer['selecao']['selecao'] = $selecao;        $buffer['selecao']['selecaoMultipla'] = $selecaoMultipla;        //Monta Paginanação        if ($qLinhas > 0) {            //Setando Valores para paginação            $this->paginacao->setTabelaMestra($tabelaMestra);            $this->paginacao->setSqlContador($sqlContador);            $this->paginacao->setFiltroAtivo($filtroAtivo);            $this->paginacao->setLimitAtivo($limitAtivo);            $this->paginacao->setChave($chave);            $this->paginacao->setAliasOrdena($aliasOrdena);            $this->paginacao->setQLinhas($qLinhas);            $this->paginacao->setProcessarNumeroPaginas($this->grid->getProcessarNumeroPaginas());            $this->paginacao->setPaginaAtual($this->grid->getPaginaAtual());            $this->paginacao->setTipoOrdenacao($this->grid->getTipoOrdenacao());            $this->paginacao->setQuemOrdena($this->grid->getQuemOrdena());            $this->paginacao->setMetodoFiltra($this->grid->getMetodoFiltra());            $this->paginacao->setAlterarLinhas($this->grid->getAlterarLinhas());            $rs = $this->paginacao->rsPaginado();        } else {            if (\is_string($sql)) {                $rs = $this->con->executar($sql);            } else {                $rs = $sql->execute();            }        }        $nLinhas = $this->con->nLinhas($rs);        //Contruindo grid        if ($nLinhas > 0) {            $buffer['paginacao'] = $this->paginacao->listaResultados();            $subs = $this->grid->getSubstituirPor();            $objC = $this->grid->getObjetoConverte();            $cTd = $this->grid->getComplementoTD();            $cssTd = $this->grid->getCssTD();            $i = 0;            while ($linha = $rs->fetch()) {                $i += 1;                if (\is_array($cTd) and ! empty($cTd)) {                    $buffer['cTd'][$linha[$chave]] = $this->grid->verificaComplementoTD($linha, $cTd);                }                if (\is_array($cssTd) and ! empty($cssTd)) {                    $buffer['cssTd'][$linha[$chave]] = $this->grid->verificaComplementoTD($linha, $cssTd);                }                foreach ($listados as $value) {                    //Valor com possivel converssão                    if (\is_array($objC) and \key_exists($value, $objC)) {                        if (isset($colunasSemHTML[$value])) {                            $valorItem = $linha[$value];                        } else {                            $valorItem = $this->grid->converteValor($linha, $objC[$value]);                        }                    } else {                        $valorItem = '';                        if (\array_key_exists($value, $linha)) {                            $valorItem = $linha[$value];                            //throw new \Exception('Grid: Valor ' . $value . ' não encontrado!');                        }                    }                    //Formatação                    if (!empty($formatarComo)) {                        if (\array_key_exists($value, $formatarComo)) {                            $como = \strtoupper($formatarComo[$value]);                            switch ($como) {                                case "DATA" : $valorItem = $fPHP->data()->converteData($valorItem);                                    break;                                case "DATAHORA": $valorItem = $fPHP->data()->converteDataHora($valorItem);                                    break;                                case "NUMERO" : $valorItem = $fPHP->numero()->floatCliente($valorItem);                                    break;                                case "MOEDA" : $valorItem = $fPHP->numero()->floatCliente($valorItem);                                    break;                                case "REAIS" : $valorItem = $fPHP->numero()->moedaCliente($valorItem);                                    break;                            }                        }                    }                    //Valor com possivel stituição                    if (\is_array($subs) and \array_key_exists($value, $subs)) {                        if (\array_key_exists($valorItem, $subs[$value])) {                            $valorItem = $subs[$value][$valorItem];                        } else {                            if ($valorItem == '') {                                $valorItem = \current($subs[$value]);                            }                        }                    }                    $buffer['valores'][$linha[$chave]][$value] = $valorItem;                }            }        }        $buffer['modoImpressao'] = $this->modoImpressao;        $buffer['legenda'] = $this->grid->getLegenda();                $buffer['queryString'] = Parametros::getQueryString();        $buffer['ordenacao'] = ['qo' => $this->grid->getQuemOrdena(), 'to' => $this->grid->getTipoOrdenacao()];        $ret = \array_merge($bufferTitulo, $buffer);        return $ret;    }#####################################################################    public function setTipoOrdenacao($valor)    {        $this->grid->setTipoOrdenacao($valor);    }    public function setQuemOrdena($valor)    {        $this->grid->setQuemOrdena($valor);    }    public function setSql($valor)    {        $this->grid->setSql($valor);    }    public function setSqlContador($valor)    {        $this->grid->setSqlContador($valor);    }    public function setFiltroAtivo($valor)    {        $this->grid->setFiltroAtivo($valor);    }    public function setLimitAtivo($valor)    {        $this->grid->setLimitAtivo($valor);    }    public function setChave($valor)    {        $this->grid->setChave($valor);    }    public function setAliasOrdena($valor)    {        $this->grid->setAliasOrdena($valor);    }    public function setMetodoFiltra($valor)    {        $this->grid->setMetodoFiltra($valor);    }    public function setQLinhas($valor)    {        $this->grid->setQLinhas($valor);    }    public function setPaginaAtual($valor)    {        $this->grid->setPaginaAtual($valor);    }    public function setIrParaPagina($valor)    {        $this->grid->setIrParaPagina($valor);    }    public function setAlterarLinhas($valor)    {        $this->grid->setAlterarLinhas($valor);    }    /**     * Monta um array representativo das colunas da tabela de um banco de dados.     * Por questões de compatibilidade as colunas serão convertidas      * automaticamente para minisculo     * @param array $arrayColunas     * @throws \Exception     */    public function setColunas($arrayColunas)    {        $this->grid->setColunas($arrayColunas);    }    public function setColunasSemHTML($arrayColunasSemHTML)    {        $this->grid->setColunasSemHTML($arrayColunasSemHTML);    }    /**     * Monta um array com informações de alinhamento de campos, pode alinhar um     * ou mais campos     * setAlinhamento(['campo1'=>'Esquerda', 'campo2'=>'Centro'],'campo3'=>'Direita');     * @param array $arrayAlinhamento     * @throws \Exception     */    public function setAlinhamento($arrayAlinhamento)    {        $this->grid->setAlinhamento($arrayAlinhamento);    }    /**     * Usa um objeto, um metodos e a indicaçãoo de como usa-los, com a função     * de converter um resultado da grid.     *      * $grid->converterResultado($this, 'mostraIcone', 'moduloClass', ['moduloClass']);     *      * @param object $objeto     * @param string $metodo     * @param string $campo     * @param array $parametrosInternos     * @param array $paremetrosExternos     * @param string $ordem     * @throws \Exception     */    public function converterResultado($objeto, $metodo, $campo, $parametrosInternos = [], $paremetrosExternos = [], $ordem = 'IE')    {        $this->grid->converterResultado($objeto, $metodo, $campo, $parametrosInternos, $paremetrosExternos, $ordem);    }    /**     * Usa um objeto, um metodo e a indicação de como usa-los, com a função     * de inserir um complemento em cada TD de resultado de uma grid     *      * $grid->complementoTD($this, 'mostraIcone', ['moduloClass']);     *      * @param object $objeto     * @param string $metodo     * @param array $parametrosInternos     * @param array $paremetrosExternos     * @param string $ordem     * @throws \Exception     */    public function complementoTD($objeto, $metodo, $parametrosInternos = [], $paremetrosExternos = [], $ordem = 'IE')    {        $this->grid->complementoTD($objeto, $metodo, $parametrosInternos, $paremetrosExternos, $ordem);    }    public function cssTD($objeto, $metodo, $parametrosInternos = [], $paremetrosExternos = [], $ordem = 'IE')    {        $this->grid->cssTD($objeto, $metodo, $parametrosInternos, $paremetrosExternos, $ordem);    }    /**     * Monta um array com informações de ordenação de campos, pode ordenar um     * ou mais campos     *      * $grid->naoOrdenePor(['moduloClass']);     *      * @param array $arrayNaoOrdenePor     * @throws \Exception     */    public function naoOrdenePor($arrayNaoOrdenePor)    {        $this->grid->naoOrdenePor($arrayNaoOrdenePor);    }    /**     * Formata um resultado da grid, pode ser (DATA, DATAHORA, NUMERO, MOEDA)     *      * $grid->setFormatarComo('moduloClass','DATA');     *      * @param string $identificacao     * @param string $como     * @throws \Exception     */    public function setFormatarComo($identificacao, $como)    {        $this->grid->setFormatarComo($identificacao, $como);    }        public function formatarComo($identificacao, $como)    {        return $this->setFormatarComo($identificacao, $como);    }    /**     * Indica se a grid deve apresentar checkbox ou radiobox de seleção      * de resultados     * @param bool $selecao     */    public function setSelecao($selecao)    {        $this->grid->setSelecao($selecao);    }    /**     * Por padrão a seleção multipla é verdadeira, no caso de setar false para      * este metodo a grid irá trazer radios para a seleção de resultados.     * @param bool $selecaoMultipla     */    public function setSelecaoMultipla($selecaoMultipla)    {        $this->grid->setSelecaoMultipla($selecaoMultipla);    }    /**     * Substitui um valor da grid por um valor equivalente em um array     *      * $grid->substituaPor('moduloVisivelMenu', ['S' => 'Sim', 'N' => 'Não']);     *      * @param string $identificacao     * @param string $por     * @throws \Exception     */    public function substituaPor($identificacao, $por)    {        $this->grid->substituaPor($identificacao, $por);    }    public function setProcessarNumeroPaginas($valor)    {        $this->grid->setProcessarNumeroPaginas($valor);    }    public function setLegenda($legenda)    {        $this->grid->setLegenda($legenda);    }    public function setLog($log)    {        if (!\is_bool($log)) {            throw new \Exception("log: o valor informado deve ser do tipo booleano.");        }        $this->log = $log;    }    public function getLog()    {        return $this->log;    }}