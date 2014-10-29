<?php/** * @author Pablo Vanni - pablovanni@gmail.com * @since 23/02/2005 * Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br> * @name  Paginação de resultado para uma consulta no banco de dados * @version 1.0 * @package Framework */namespace Zion\Paginacao;class Paginacao extends \Zion\Paginacao\PaginacaoVO{    private $con;    private $resultado;    private $atributos;        private $html;    /**     * Paginacao::__construct()     *      * @return     */    public function __construct($con = NULL)    {        parent::__construct();        $this->html = new \Zion\Layout\Html();        if (!$con) {            $this->con = \Zion\Banco\Conexao::conectar();        } else {            $this->con = $con;        }    }    /**     * 	Retorna um ResultSet com um numero determinado de QLinhas     * 	@param QLinhas Inteiro - Número de QLinhas a retotnar no RS     * 	@param Sql String - Query SQL que irá selecionar os dados     * 	@param PaginaAtual Inteiro - Página atual dos QLinhas     * 	@param Chave Inteiro - Campo Chave pelo qual deve ser ordenado os resultados     * 	@param QuemOrdena Inteiro - Número de QLinhas a retotnar no RS     * 	@param TipoOrdenacao String - Número de QLinhas a retotnar no RS     * 	@param Ordena String - Número de QLinhas a retotnar no RS     * 	@return ResultSet     */    public function rsPaginado()    {        $qLinhas = parent::getQLinhas();        $sql = parent::getSql();        $paginaAtual = parent::getPaginaAtual();        $chave = parent::getChave();        $quemOrdena = parent::getQuemOrdena();        $limitAtivo = parent::getLimitAtivo();        //Extremo dos Proximos QLinhas        $inicio = ($paginaAtual == 1) ? 0 : (($paginaAtual * $qLinhas) - $qLinhas);        //Verifica Ordenção        if (!empty($quemOrdena)) {            $ordem = " ORDER BY " . $quemOrdena . " " . parent::getTipoOrdenacao();        } else {            $ordem = " ORDER BY " . $chave . " " . parent::getTipoOrdenacao();        }        //Não é Paginado        if ($qLinhas == 0) {            return $this->con->executar($sql . " " . $ordem);        }        //Definir Limit        if ($limitAtivo) {            $limit = ($qLinhas <> 0) ? " LIMIT " . $inicio . "," . $qLinhas : "";        }        //Retorno        $rS = $this->con->executar($sql . $ordem . $limit);        return $rS;    }    /**     * 	Retorna um ResultSet com um numero determinado de QLinhas     * 	@param QLinhas Inteiro - Número de QLinhas a retotnar no RS     * 	@param Sql String - Query SQL que irá selecionar os dados     * 	@param PaginaAtual Inteiro - Página atual dos QLinhas     * 	@param IrParaPagina Booleano - Ir diretamente para a página desejada habilitar ou não esta opação na paginação     * 	@return Booleano     */    public function listaResultados()    {        $qLinhas = parent::getQLinhas();        $paginaAtual = parent::getPaginaAtual();        $quemOrdena = parent::getQuemOrdena();        $metodoFiltra = parent::getMetodoFiltra();        if (substr_count(strtoupper(parent::getSql()), 'SELECT ') > 1) {            $numLinhas = $this->con->execNLinhas(parent::getSql());        } else {            $numLinhas = $this->con->execRLinha($this->converteSql(parent::getSql()));        }        //Total de Páginas        $totalPaginas = ceil($numLinhas / $qLinhas);        $final = $totalPaginas <= 1 ? $numLinhas : $qLinhas;        //Imprimindo QLinhas        if ($totalPaginas > 1) {            //Verifica se existe variavel para QuemOrdena de ordenação            if (!empty($quemOrdena)) {                Parametros::setParametros("Full", array("qo" => $quemOrdena));            }            $anterior = '';            $proximo = '';            //Anterior            if ($paginaAtual > 1) {                Parametros::setParametros("Full", array("pa" => ($paginaAtual - 1)));                $onclick = $metodoFiltra . '(\'' . Parametros::getQueryString() . '\'); sisSpa(\'' . ($paginaAtual - 1) . '\');';                $anterior  = $this->html->abreTagAberta('button', array('type' => 'button', 'title' => 'voltar', 'onclick' => $onclick), array('%button-rew%'));                $anterior .= $this->html->abreTagAberta('i', array(), array('%i-rew%'));                $anterior .= $this->html->fechaTag('i');                $anterior .= $this->html->fechaTag('button');            } else {                $anterior  = $this->html->abreTagAberta('button', array('type' => 'button', 'title' => 'voltar'), array('%button-rew-off%'));                $anterior .= $this->html->abreTagAberta('i', array(), array('%i-rew%'));                $anterior .= $this->html->fechaTag('i');                $anterior .= $this->html->fechaTag('button');            }            //Proxima            if ($paginaAtual < $totalPaginas) {                Parametros::setParametros("Full", array("pa" => ($paginaAtual + 1)));                $onclick = $metodoFiltra . '(\'' . Parametros::getQueryString() . '\'); sisSpa(\'' . ($paginaAtual + 1) . '\');';                $proximo  = $this->html->abreTagAberta('button', array('type' => 'button', 'title' => 'avançar', 'onclick' => $onclick), array('%button-fwd%'));                $proximo .= $this->html->abreTagAberta('i', array(), array('%i-fwd%'));                $proximo .= $this->html->fechaTag('i');                $proximo .= $this->html->fechaTag('button');            } else {                $proximo  = $this->html->abreTagAberta('button', array('type' => 'button', 'title' => 'avançar'), array('%button-fwd-off%'));                $proximo .= $this->html->abreTagAberta('i', array(), array('%i-fwd%'));                $proximo .= $this->html->fechaTag('i');                $proximo .= $this->html->fechaTag('button');            }            //Calculo de Páginas            if ($paginaAtual == 1) {                $iPAG = 1;                $fPAG = $numLinhas > $qLinhas ? $qLinhas : $numLinhas;                                $icPrimPag  = $this->html->abreTagAberta('li', array(), array('%li-fp%'));                $icPrimPag .= $this->html->abreTagAberta('a', array('onclick' => 'sisFiltrar(\'pa=1\'); sisSpa(\'1\');'), array('nohref'));                $icPrimPag .= $this->html->abreTagAberta('i', array(), array('%i-fp%'));                $icPrimPag .= '&nbsp;Primeira p&aacute;gina';                $icPrimPag .= $this->html->fechaTag('i');                $icPrimPag .= $this->html->fechaTag('a');                $icPrimPag .= $this->html->fechaTag('li');                $icUltPag  = $this->html->abreTagAberta('li', array(), array('%li-lp%'));                $icUltPag .= $this->html->abreTagAberta('a', array('onclick' => 'sisFiltrar(\'pa='.$totalPaginas.'\'); sisSpa(\''.$totalPaginas.'\');'), array('nohref'));                $icUltPag .= $this->html->abreTagAberta('i', array(), array('%i-lp%'));                $icUltPag .= '&nbsp;&Uacute;ltima p&aacute;gina';                $icUltPag .= $this->html->fechaTag('i');                $icUltPag .= $this->html->fechaTag('a');                $icUltPag .= $this->html->fechaTag('li');                            } else {                $iPAG = ((($paginaAtual - 1) * $qLinhas) + 1);                $fPAG = $paginaAtual == $totalPaginas ? $numLinhas : $paginaAtual * $qLinhas;                $icPrimPag  = $this->html->abreTagAberta('li', array(), array('%li-fp%'));                $icPrimPag .= $this->html->abreTagAberta('a', array('onclick' => 'sisFiltrar(\'pa=1\'); sisSpa(\'1\');'), array('nohref'));                $icPrimPag .= $this->html->abreTagAberta('i', array(), array('%i-fp%'));                $icPrimPag .= '&nbsp;Primeira p&aacute;gina';                $icPrimPag .= $this->html->fechaTag('i');                $icPrimPag .= $this->html->fechaTag('a');                $icPrimPag .= $this->html->fechaTag('li');                //$icUltPag  = '<li %li-lp%><a nohref onclick="sisFiltrar(\'pa='.$totalPaginas.'\'); sisSpa(\''.$totalPaginas.'\');"><i %i-lp%></i>&nbsp;&Uacute;ltima p&aacute;gina</a></li>';                $icUltPag  = $this->html->abreTagAberta('li', array(), array('%li-lp%'));                $icUltPag .= $this->html->abreTagAberta('a', array('onclick' => 'sisFiltrar(\'pa='.$totalPaginas.'\'); sisSpa(\''.$totalPaginas.'\');'), array('nohref'));                $icUltPag .= $this->html->abreTagAberta('i', array(), array('%i-lp%'));                $icUltPag .= '&nbsp;&Uacute;ltima p&aacute;gina';                $icUltPag .= $this->html->fechaTag('i');                $icUltPag .= $this->html->fechaTag('a');                $icUltPag .= $this->html->fechaTag('li');                            }            $retorno  = $this->html->abreTagAberta('div', array(), array('%div-drop%'));            $retorno .= $this->html->abreTagAberta('div', array(), array('%div-drop-group%'));            $retorno .= $this->html->abreTagAberta('button', array('data-toggle' => 'dropdown'), array('%button-drop%'));            $retorno .= $this->html->abreTagAberta('i', array(), array('%i-drop%'));            $retorno .= '&nbsp;';            $retorno .= $this->html->fechaTag('i');            $retorno .= $this->html->abreTagAberta('i', array(), array('%i-drop-caret%'));            $retorno .= $this->html->fechaTag('i');            $retorno .= $this->html->fechaTag('button');            $retorno .= $this->html->abreTagAberta('ul', array(), array('%ul-drop%'));            $retorno .= $icPrimPag;            $retorno .= $icUltPag;            $retorno .= $this->html->fechaTag('ul');            $retorno .= $this->html->fechaTag('div');            $retorno .= $this->html->abreTagAberta('div', array(), array('%div-drop-group-items%'));            $retorno .= $anterior;            $retorno .= $proximo;            $retorno .= $this->html->fechaTag('div');            $retorno .= $this->html->fechaTag('div');            $resultado  = $this->html->abreTagAberta('div', array(), array('%div-rols%'));            $resultado .= $this->html->abreTagAberta('span', array(), array('%span-rols%'));            $resultado .= 'Mostrando de ' . $iPAG . ' a ' . $fPAG . ' de ' . $numLinhas . ' registro(s)';            $resultado .= $this->html->fechaTag('span');            $resultado .= '&nbsp;&nbsp;';            $resultado .= $this->html->abreTagAberta('span', array(), array('%span-rols%'));            $resultado .= 'P&aacute;gina ' . $paginaAtual . ' de ' . $totalPaginas . ' p&aacute;gina(s)';            $resultado .= $this->html->fechaTag('span');            $resultado .= $this->html->fechaTag('div');        } else {            $resultado  = $this->html->abreTagAberta('div', array(), array('%div-fp-off%'));            $resultado .= $this->html->entreTags('em', $final . ' de ' . $numLinhas);            $resultado .= $this->html->fechaTag('div');            $retorno  = $this->html->abreTagAberta('div', array(), array('%div-fp-off%'));            $retorno .= $this->html->abreTagAberta('button', array('type' => 'button', 'title' => 'voltar'), array('%button-rew-off%'));            $retorno .= $this->html->abreTagAberta('i', array(), array('%i-rew%'));            $retorno .= $this->html->fechaTag('i');            $retorno .= $this->html->fechaTag('button');            $retorno .= $this->html->abreTagAberta('button', array('type' => 'button', 'title' => 'avançar'), array('%button-fwd-off%'));            $retorno .= $this->html->abreTagAberta('i', array(), array('%i-fwd%'));            $retorno .= $this->html->fechaTag('i');            $retorno .= $this->html->fechaTag('button');            $retorno .= $this->html->fechaTag('div');        }                $this->resultado = $this->setTemplateInterface($resultado);        return $this->setTemplateInterface($retorno);            }    /**     * Paginacao::getResultado()     *      * @return     */    public function getResultado()    {        return $this->resultado;    }    /**     * Paginacao::converteSql()     *      * @return     */    private function converteSql($sql)    {        return preg_replace('/SELECT.*FROM/i', 'SELECT COUNT(*) as Total FROM ', preg_replace('/\s/i', ' ', $sql));    }    private function setTemplateInterface($html){        $this->atributos = $this->getAtributos();        $vars = array_keys($this->atributos);        $regex = array_map(function($value){                     return "/%". $value ."%/";                 }, $vars);        $retorno = preg_replace_callback($regex, function($match){ return $this->getAttrClass(substr($match[0], 1, -1));}, $html);        return preg_replace('/\s>/', '>', $retorno);    }        private function getAttrClass($var){                $attrs = $this->atributos;                $class = (empty($attrs[$var]) ? "" : 'class="'. $attrs[$var] .'"');        return $class;    }        private function getAtributos(){                $attrs = array(                    "div-drop"              => parent::getDivDrop(),                    "div-drop-group"        => parent::getDivDropGroup(),                    "div-drop-group-items"  => parent::getDivDropGroupItems(),                    "div-fp-off"            => parent::getDivFpOff(),                    "div-pag-off"           => parent::getDivPagOff(),                    "div-rols"              => parent::getDivRols(),                    "i-drop"                => parent::getIDrop(),                    "i-drop-caret"          => parent::getIDropCaret(),                    "i-rew"                 => parent::getIRew(),                    "i-fwd"                 => parent::getIFwd(),                    "i-fp"                  => parent::getIFp(),                    "i-lp"                  => parent::getILp(),                    "ul-drop"               => parent::getUlDrop(),                    "li-fp"                 => parent::getLiFp(),                    "li-lp"                 => parent::getLiLp(),                    "span-rols"             => parent::getSpanRols(),                    "button-drop"           => parent::getButtonDrop(),                    "button-rew"            => parent::getButtonRew(),                    "button-fwd"            => parent::getButtonFwd(),                    "button-rew-off"        => parent::getButtonRewOff(),                    "button-fwd-off"        => parent::getButtonFwdOff()                 );                         return $attrs;            }}