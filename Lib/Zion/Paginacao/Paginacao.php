<?php/** * @author Pablo Vanni - pablovanni@gmail.com * @since 23/02/2005 * Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br> * @name  Paginação de resultado para uma consulta no banco de dados * @version 1.0 * @package Framework */namespace Zion\Paginacao;class Paginacao extends \Zion\Paginacao\PaginacaoVO{    private $con;    private $resultado;    /**     * Paginacao::__construct()     *      * @return     */    public function __construct($con = NULL)    {        parent::__construct();        if (!$con) {            $this->con = \Zion\Banco\Conexao::conectar();        } else {            $this->con = $con;        }    }    /**     * 	Retorna um ResultSet com um numero determinado de QLinhas     * 	@param QLinhas Inteiro - Número de QLinhas a retotnar no RS     * 	@param Sql String - Query SQL que irá selecionar os dados     * 	@param PaginaAtual Inteiro - Página atual dos QLinhas     * 	@param Chave Inteiro - Campo Chave pelo qual deve ser ordenado os resultados     * 	@param QuemOrdena Inteiro - Número de QLinhas a retotnar no RS     * 	@param TipoOrdenacao String - Número de QLinhas a retotnar no RS     * 	@param Ordena String - Número de QLinhas a retotnar no RS     * 	@return ResultSet     */    public function rsPaginado()    {        $qLinhas = parent::getQLinhas();        $sql = parent::getSql();        $paginaAtual = parent::getPaginaAtual();        $chave = parent::getChave();        $quemOrdena = parent::getQuemOrdena();        $limitAtivo = parent::getLimitAtivo();        //Extremo dos Proximos QLinhas        $inicio = ($paginaAtual == 1) ? 0 : (($paginaAtual * $qLinhas) - $qLinhas);        //Verifica Ordenção        if (!empty($quemOrdena)) {            $ordem = " ORDER BY " . $quemOrdena . " " . parent::getTipoOrdenacao();        } else {            $ordem = " ORDER BY " . $chave . " " . parent::getTipoOrdenacao();        }        //Não é Paginado        if ($qLinhas == 0) {            return $this->con->executar($sql . " " . $ordem);        }        //Definir Limit        if ($limitAtivo) {            $limit = ($qLinhas <> 0) ? " LIMIT " . $inicio . "," . $qLinhas : "";        }        //Retorno        $rS = $this->con->executar($sql . $ordem . $limit);        return $rS;    }    /**     * 	Retorna um ResultSet com um numero determinado de QLinhas     * 	@param QLinhas Inteiro - Número de QLinhas a retotnar no RS     * 	@param Sql String - Query SQL que irá selecionar os dados     * 	@param PaginaAtual Inteiro - Página atual dos QLinhas     * 	@param IrParaPagina Booleano - Ir diretamente para a página desejada habilitar ou não esta opação na paginação     * 	@return Booleano     */    public function listaResultados()    {        $qLinhas = parent::getQLinhas();        $paginaAtual = parent::getPaginaAtual();        $quemOrdena = parent::getQuemOrdena();        $metodoFiltra = parent::getMetodoFiltra();        if (substr_count(strtoupper(parent::getSql()), 'SELECT ') > 1) {            $numLinhas = $this->con->execNLinhas(parent::getSql());        } else {            $numLinhas = $this->con->execRLinha($this->converteSql(parent::getSql()));        }        //Total de Páginas        $totalPaginas = ceil($numLinhas / $qLinhas);        $final = $totalPaginas <= 1 ? $numLinhas : $qLinhas;        //Imprimindo QLinhas        if ($totalPaginas > 1) {            //Verifica se existe variavel para QuemOrdena de ordenação            if (!empty($quemOrdena)) {                Parametros::setParametros("Full", array("qo" => $quemOrdena));            }            $anterior = '';            $proximo = '';            //Anterior            if ($paginaAtual > 1) {                Parametros::setParametros("Full", array("pa" => ($paginaAtual - 1)));                $onclick = $metodoFiltra . '(\'' . Parametros::getQueryString() . '\'); sisSpa(\'' . ($paginaAtual - 1) . '\');';                $anterior = '<button type="button" title="Voltar" onclick="' . $onclick . '" %button-rew%><i %i-rew%></i></button>';            } else {                $anterior = '<button type="button" title="Voltar" %button-rew%><i %i-rew%></i></button>';            }            //Proxima            if ($paginaAtual < $totalPaginas) {                Parametros::setParametros("Full", array("pa" => ($paginaAtual + 1)));                $onclick = $metodoFiltra . '(\'' . Parametros::getQueryString() . '\'); sisSpa(\'' . ($paginaAtual + 1) . '\');';                $proximo = '<button type="button" title="Avan&ccedil;ar" onclick="' . $onclick . '" %button-fwd%><i %i-fwd%></i></button>';            } else {                $proximo = '<button type="button" title="Avan&ccedil;ar" %button-fwd%><i %i-fwd%></i></button>';            }            //Calculo de Páginas            if ($paginaAtual == 1) {                $iPAG = 1;                $fPAG = $numLinhas > $qLinhas ? $qLinhas : $numLinhas;                $icPrimPag = '<li %li-fp%><a nohref onclick="sisFiltrar(\'pa=1\'); sisSpa(\'1\');"><i %i-fp%></i>&nbsp;Primeira p&aacute;gina</a></li>';                $icUltPag  = '<li %li-lp%><a nohref onclick="sisFiltrar(\'pa='.$totalPaginas.'\'); sisSpa(\''.$totalPaginas.'\');"><i %li-lp%></i>&nbsp;&Uacute;ltima p&aacute;gina</a></li>';            } else {                $iPAG = ((($paginaAtual - 1) * $qLinhas) + 1);                $fPAG = $paginaAtual == $totalPaginas ? $numLinhas : $paginaAtual * $qLinhas;                $icPrimPag = '<li %li-fp%><a nohref onclick="sisFiltrar(\'pa=1\'); sisSpa(\'1\');"><i %i-fp%></i>&nbsp;Primeira p&aacute;gina</a></li>';                $icUltPag  = '<li %li-lp%><a nohref onclick="sisFiltrar(\'pa='.$totalPaginas.'\'); sisSpa(\''.$totalPaginas.'\');"><i %i-lp%></i>&nbsp;&Uacute;ltima p&aacute;gina</a></li>';            }            //Parte C            //$retorno  = '<div class=" alinD"><em>' . $iPAG . '-' . $fPAG . ' de ' . $numLinhas . '</em></div>';             $retorno = '<div %div-drop%>';            $retorno .= '<div %div-drop-group%>                            <button data-toggle="dropdown" %button-drop%>                                <i %i-drop%>&nbsp;</i>                                <i %i-drop-caret%></i>                            </button>                            <ul %ul-drop%>                                '.$icPrimPag.'                                '.$icUltPag.'                            </ul>                        </div>                            <div %div-drop-group-items%>                                ' . $anterior . '                                ' . $proximo . '                            </div>            			</div>';            $this->resultado = '<div %div-rols%><span %span-rols%>Mostrando de ' . $iPAG . ' a ' . $fPAG . ' de ' . $numLinhas . ' registro(s)</span>&nbsp;&nbsp;<span %span-rols%>P&aacute;gina ' . $paginaAtual . ' de ' . $totalPaginas . ' p&aacute;gina(s)</span></div>';            return $retorno;        } else {            $this->resultado = '<div %div-fp-off%><em>' . $final . ' de ' . $numLinhas . '</em></div>';            return '<div>				<div>					<button type="button" title="Voltar" %button-rew-off%><i></i></button>					<button type="button" title="Avan&ccedil;ar" %button-fwd-off%><i></i></button>				</div>			</div>';        }    }    /**     * Paginacao::getResultado()     *      * @return     */    public function getResultado()    {        return $this->resultado;    }    /**     * Paginacao::converteSql()     *      * @return     */    private function converteSql($sql)    {        return preg_replace('/SELECT.*FROM/i', 'SELECT COUNT(*) as Total FROM ', preg_replace('/\s/i', ' ', $sql));    }}