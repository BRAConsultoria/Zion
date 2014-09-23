<?/** * @author Pablo Vanni - pablovanni@gmail.com * @since 23/02/2005 * Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br> * @name  Paginação de resultado para uma consulta no banco de dados * @version 1.0 * @package Framework */namespace Zion\Form;class Paginacao extends PaginacaoVO{    private $con;    public function __construct($con = NULL)    {        if (!$con) {            $this->con = Conexao::conectar();        } else {            $this->con = $con;        }    }    /**     * 	Retorna um ResultSet com um numero determinado de QLinhas     * 	@param QLinhas Inteiro - Número de QLinhas a retotnar no RS     * 	@param Sql String - Query SQL que irá selecionar os dados     * 	@param PaginaAtual Inteiro - Página atual dos QLinhas     * 	@param Chave Inteiro - Campo Chave pelo qual deve ser ordenado os resultados     * 	@param QuemOrdena Inteiro - Número de QLinhas a retotnar no RS     * 	@param TipoOrdenacao String - Número de QLinhas a retotnar no RS     * 	@param Ordena String - Número de QLinhas a retotnar no RS     * 	@return ResultSet     */    public function rsPaginado()    {        $qLinhas = parent::getQLinhas();        $sql = parent::getSql();        $paginaAtual = parent::getPaginaAtual();        $chave = parent::getChave();        $quemOrdena = parent::getQuemOrdena();        $alterarLinhas = parent::getAlterarLinhas();        $limitAtivo = parent::getLimitAtivo();        //QLinhas        try {            if ($alterarLinhas === true) {                if (MODULO and ! empty($_SESSION['UsuarioCod'])) {                    $numeroRegistros = $this->con->execRLinha("SELECT Registros FROM _usuario_paginacao WHERE ModuloNome = '" . MODULO . "' AND UsuarioCod =" . $_SESSION['UsuarioCod'], "Registros");                } else {                    $numeroRegistros = 0;                }                $qLinhas = (empty($numeroRegistros)) ? parent::getQLinhas() : $numeroRegistros;                parent::setQLinhas($qLinhas);            } else {                $qLinhas = parent::getQLinhas();            }        } catch (Exception $e) {            $qLinhas = parent::getQLinhas();        }        //Extremo dos Proximos QLinhas        $inicio = ($paginaAtual == 1) ? 0 : (($paginaAtual * $qLinhas) - $qLinhas);        //Verifica Ordenção        if (!empty($quemOrdena)) {            $ordem = " ORDER BY " . $quemOrdena . " " . parent::getTipoOrdenacao();        } else {            $ordem = " ORDER BY " . $chave . " " . parent::getTipoOrdenacao();        }        //Não é Paginado        if ($qLinhas == 0) {            return $this->con->executar($sql . " " . $ordem);        }        //Definir Limit        if ($limitAtivo)            $limit = ($qLinhas <> 0) ? " LIMIT " . $inicio . "," . $qLinhas : "";        //Retorno        $rS = $this->con->executar($sql . $ordem . $limit);        return $rS;    }    /**     * 	Retorna um ResultSet com um numero determinado de QLinhas     * 	@param QLinhas Inteiro - Número de QLinhas a retotnar no RS     * 	@param Sql String - Query SQL que irá selecionar os dados     * 	@param PaginaAtual Inteiro - Página atual dos QLinhas     * 	@param IrParaPagina Booleano - Ir diretamente para a página desejada habilitar ou não esta opação na paginação     * 	@return Booleano     */    public function listaResultados()    {        $qLinhas = parent::getQLinhas();        $paginaAtual = parent::getPaginaAtual();        $quemOrdena = parent::getQuemOrdena();        $metodoFiltra = parent::getMetodoFiltra();        $irParaPagina = parent::getIrParaPagina();        $alterarLinhas = parent::getAlterarLinhas();        //Intancia Formulário        $form = new \Zion\Form\Form();        if (substr_count(strtoupper(parent::getSql()), 'SELECT ') > 1) {            $numLinhas = $this->con->execNLinhas(parent::getSql());        } else {            $numLinhas = $this->con->execRLinha($this->converteSql(parent::getSql()));        }        //Total de Páginas        $totalPaginas = ceil($numLinhas / $qLinhas);        //Paginação Dinamica        if ($alterarLinhas === true) {            $aGeradoT = range(1, 200);            $arrayPaginasT = array_combine($aGeradoT, $aGeradoT);            //Gera Campo ListBox            $form->escolha()                    ->setNome('SIS_N_REGISTROS')                    ->setExpandido(false)                    ->setMultiplo(false)                    ->setValor($qLinhas)                    ->setInicio(false)                    ->setOrdena(false)                    ->setComplemento('class="sis_alterar_paginacao_form" onChange="sis_altera_paginacao(this.value,\'' . MODULO . '\')"')                    ->setArray($arrayPaginasT);                                   $nRegistros = '<span class="sis_alterar_paginacao">Mostrando ' . $nRegistrosForm . ' registros por página</span>';        } else {            $nRegistrosForm = $qLinhas;            $nRegistros = '';        }        //Imprimindo QLinhas        if ($totalPaginas > 1) {            //Verifica se existe variavel para QuemOrdena de ordenação            if (!empty($quemOrdena))                Parametros::setParametros("Full", array("QuemOrdena" => $quemOrdena));            //Para List            $parametrosList = Parametros::getQueryString();            //Primeira            if ($paginaAtual > 1) {                Parametros::setParametros("Full", array("PaginaAtual" => 1));                $parte1 .= "<span class=\"paginacao\"><a href=\"javascript:" . $metodoFiltra . "('" . Parametros::getQueryString() . "'); sis_spa('1');\" class=\"linkpag\"><img src=\"" . $_SESSION['UrlBase'] . "figuras/vol.gif\" border=\"0\"/>&nbsp;Primeira</a></span>&nbsp;\n";            } else {                $parte1 .= "<span class=\"paginacaoOff\"><img src=\"" . $_SESSION['UrlBase'] . "figuras/vol_of.gif\"/>&nbsp;Primeira</span>&nbsp;\n";            }            //Próximo            if ($paginaAtual > 1) {                Parametros::setParametros("Full", array("PaginaAtual" => ($paginaAtual - 1)));                $parte1 .= "<span class=\"paginacao\"><a href=\"javascript:" . $metodoFiltra . "('" . Parametros::getQueryString() . "'); sis_spa('" . ($paginaAtual - 1) . "');\" class=\"linkpag\"><img src=\"" . $_SESSION['UrlBase'] . "figuras/vol1.gif\" border=\"0\"/>&nbsp;Anterior</a></span>&nbsp;\n";            } else {                $parte1 .= "<span class=\"paginacaoOff\"><img src=\"" . $_SESSION['UrlBase'] . "figuras/vol1_of.gif\"/>&nbsp;Anterior</span>&nbsp;\n";            }            //Anterior            if ($paginaAtual < $totalPaginas) {                Parametros::setParametros("Full", array("PaginaAtual" => ($paginaAtual + 1)));                $parte3 .= "<span class=\"paginacao\"><a href=\"javascript:" . $metodoFiltra . "('" . Parametros::getQueryString() . "'); sis_spa('" . ($paginaAtual + 1) . "'); \" class=\"linkpag\">Próxima&nbsp;<img src=\"" . $_SESSION['UrlBase'] . "figuras/ava1.gif\" border=\"0\"/></a></span>&nbsp;\n";            } else {                $parte3 .= "<span class=\"paginacaoOff\">Próximo&nbsp;<img src=\"" . $_SESSION['UrlBase'] . "figuras/ava1_of.gif\" border=\"0\"/></strong></span>&nbsp;\n";            }            //Última            if ($paginaAtual < $totalPaginas) {                Parametros::setParametros("Full", array("PaginaAtual" => $totalPaginas));                $parte3 .= "<span class=\"paginacao\"><a href=\"javascript:" . $metodoFiltra . "('" . Parametros::getQueryString() . "'); sis_spa('" . ($totalPaginas) . "');\" class=\"linkpag\">Última&nbsp;<img src=\"" . $_SESSION['UrlBase'] . "figuras/ava.gif\" border=\"0\"/></a></span>&nbsp;\n";            } else {                $parte3 .= "<span class=\"paginacaoOff\">Último&nbsp;<img src=\"" . $_SESSION['UrlBase'] . "figuras/ava_of.gif\" border=\"0\"/></strong></span>&nbsp;\n";            }            //Calculo de Páginas            if ($paginaAtual == 1) {                $iPAG = 1;                $fPAG = $numLinhas > $qLinhas ? $qLinhas : $numLinhas;            } else {                $iPAG = ((($paginaAtual - 1) * $qLinhas) + 1);                $fPAG = $paginaAtual == $totalPaginas ? $numLinhas : $paginaAtual * $qLinhas;            }            //Parte A            $retorno .= $parte1 . "&nbsp;";            //Parte B - Ir para Página            if ($irParaPagina === true) {                Parametros::setParametros("Full", array("PaginaAtual" => $paginaAtual));                //Gerando Arrays de Paginacao                $aGerado = range(1, $totalPaginas);                $arrayPaginas = array_combine($aGerado, $aGerado);                //Gera Campo ListBox                $campoPaginas = $form->listaVetor(array(                    "Nome" => "SIS_PAGINA",                    "Identifica" => "Página",                    "Valor" => $paginaAtual,                    "Inicio" => false,                    "Status" => true,                    "Ordena" => false,                    "Adicional" => 'class="PaginacaoSelect" onChange="' . $metodoFiltra . '(\'' . $parametrosList . '\&PaginaAtual=\'+this.value); sis_spa(this.value);"',                    "Vetor" => $arrayPaginas), false);                $retorno.= '<span id="sis_ir_para_pagina">' . $campoPaginas . '</span>';            }            //Parte C            $retorno .= "&nbsp;&nbsp;" . $parte3 . "&nbsp;&nbsp;&nbsp;<span class=\"paginacaoOff\"> " . $iPAG . " - " . $fPAG . " de " . $numLinhas . "</span>" . $nRegistros;            return $retorno;        } else {            $resultado = $numLinhas . ' registro' . ($numLinhas > 1 ? 's' : '') . ' encontrado' . ($numLinhas > 1 ? 's' : '');            return '<span class="sis_alterar_paginacao_so">' . $resultado . ' - Mostrando até ' . $nRegistrosForm . ' registros por página </span>';        }    }    private function converteSql($sql)    {        $sql = preg_replace('/\s/i', ' ', $sql);        $sql = preg_replace('/SELECT.*FROM/i', 'SELECT COUNT(*) as Total FROM ', $sql);        return $sql;    }}