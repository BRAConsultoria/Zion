<?php/** * @author Pablo Vanni - pablovanni@gmail.com * @since 01/06/2006 * Última Atualização: 28/05/2007 * Autualizada Por: Pablo Vanni - pablovanni@gmail.com * @name Metodos de interação com a base de dados * @version 2.0 * @package Framework */namespace Zion\Menu;abstract class MenuSql{    protected $con;    /**     * MenuSql::__construct()     *      * @return     */    public function __construct()    {        $this->con = \Zion\Banco\Conexao::conectar();    }    /**     * 	Retorna os Grupos disponíveis para determinado usuário     * 	Utilizado para gerar tamanho do menu     * 	@return Quantidade de Grupos     */    protected function gruposDiponiveisUsuario($usuarioCod)    {        $sql = "SELECT a.grupoCod                 FROM _grupo a INNER JOIN  _modulo b ON (a.moduloCod = b.moduloCod)                     INNER JOIN _acao_modulo c ON (b.moduloCod = c.moduloCod)                      INNER JOIN _permissao d ON (c.acaomoduloCod = d.acaomoduloCod)                WHERE d.usuarioCod = $usuarioCod LIMIT 1";        return $this->con->execNLinhas($sql);    }    /**     * MenuSql::gruposDiponiveisSql()     *      * @return     */    protected function gruposDiponiveisSql()    {        $sql = "SELECT 	 grupoCod, grupoNome, grupoPacote, grupoClass                FROM	 _grupo                ORDER BY grupoPosicao ASC";        return $sql;    }    /**     * MenuSql::modulosDiponiveisSql()     *      * @return     */    protected function modulosDiponiveisSql()    {        $sql = "SELECT 	 moduloCod, grupoCod, moduloCodReferente, moduloNome,                          moduloDesc, moduloNomeMenu, moduloBase, moduloVisivelMenu, moduloClass                FROM	 _modulo                 WHERE    1 = 1                 ORDER BY moduloPosicao ASC";        return $sql;    }    /**     * MenuSql::usuarioPermissaoModuloSql()     *      * @param mixed $usuarioCod     * @return     */    protected function usuarioPermissaoModuloSql($usuarioCod)    {        $sql = "SELECT DISTINCT(moduloCod) as moduloCod                FROM   _acao_modulo a INNER JOIN _permissao b ON (a.acaomoduloCod = b.acaomoduloCod)                WHERE  b.tipoPermissaoCod IS NOT NULL                AND    b.usuarioCod = $usuarioCod";        return $sql;    }    /**     * MenuSql::dadosModulo()     *      * @param mixed $moduloCod     * @param bool $visivel     * @return     */    protected function dadosModulo($moduloCod, $visivel = true)    {        $visibilidade = $visivel == false ? "" : " AND VisivelMenu = 'S' ";        $sql = "SELECT a.moduloCod, a.grupoCod, a.moduloNome,                       a.moduloDesc, a.NomeMenu, a.moduloBase,                       b.pacote                FROM   _modulos a, _grupomodulo b                WHERE  a.grupoCod = b.grupoCod                       $visibilidade AND                       a.moduloCod = $moduloCod";        return $this->con->execLinha($sql);    }    /**     * MenuSql::modulosReferentes()     * Retorna os grupos referentes     *      * @param mixed $referencia     * @param bool $visivel     * @return     */    protected function modulosReferentes($referencia, $visivel = true)    {        $visibilidade = $visivel == false ? "" : " AND VisivelMenu = 'S' ";        $sql = "SELECT 	 moduloCod, NomeMenu                FROM	 _modulos                 WHERE 	 moduloReferente = " . $referencia . "                           $visibilidade                ORDER BY posicao ASC";        return $this->con->executar($sql);    }    /**     * 	Retorna os m�dulos disponiveis no sistema para cada grupo     * 	@param GrupoCod String - C�digo do Grupo     * 	@param Mostrar String  - T -> Todos, V ->Visiveis no menu     * 	@return ResultSet     */    protected function modulosGrupoSemReferencia($grupoCod, $mostrar = "V")    {        $condicaoMostrar = ($mostrar == "V") ? " AND a.VisivelMenu = 'S' " : "";        $sql = "SELECT a.moduloCod                FROM   _modulos a, _grupomodulo b                 WHERE  a.grupoCod = b.grupoCod                         $condicaoMostrar                        AND a.grupocod= " . $grupoCod . "                        AND a.moduloReferente = 0                 ORDER BY a.posicao ASC";        return $this->con->executar($sql);    }    /**     * MenuSql::existeSubModulo()     *      * @param mixed $moduloCod     * @param string $mostrar     * @return     */    protected function existeSubModulo($moduloCod, $mostrar = "V")    {        $condicaoMostrar = ($mostrar == "V") ? " VisivelMenu = 'S' AND" : "";        $sql = "SELECT moduloCod FROM _modulos WHERE $condicaoMostrar moduloReferente = $moduloCod ";        return ($this->con->execNLinhas($sql) > 0) ? true : false;    }    /**     * 	Retorna o SQL para o n�mero de permiss�es ativas para um grupo inteiro     * 	@param GrupoCod String - C�digo do Grupo     * 	@return String     */    protected function sqlPermissaoGrupo($grupoCod)    {        $sql = "SELECT count(a.grupoCod) Total                FROM   _grupomodulo a, _modulos b,                        _opcoes_modulo c, _usuarios d,                       _tipo_permissao e                   WHERE  a.grupoCod         = b.grupoCod                        AND b.moduloCod    = c.moduloCod                          AND e.usuarioCod   = d.usuarioCod                         AND c.opcoesmoduloCod = e.opcoesmoduloCod                         AND d.usuarioCod   = " . $_SESSION['usuarioCod'] . "                         AND a.grupoCod     = " . $grupoCod . "                        AND e.permissao    = 'S'";        return $sql;    }    /**     * 	Retorna o n�mero de permiss�es ativas para um grupo inteiro     * 	@param GrupoCod String - C�digo do Grupo     * 	@return Inteiro     */    protected function permissaoGrupo($grupoCod)    {        $totalDiretos = $this->con->execRLinha($this->sqlPermissaoGrupo($grupoCod));        $totalGeral = $totalDiretos; // + $contRef;        return ((int) $totalGeral);    }    /**     * 	Retorna o n�mero Permiss�es ativas para um m�dulo especifico     * 	@param GrupoCod String - C�digo do Grupo     * 	@param moduloCod String - C�digo do M�dulo     * 	@return Inteiro     */    protected function ocorrenciasModulo($grupoCod, $moduloCod)    {        //Verifica o numero de ocorrencias para este grupo        $sql = "SELECT count(a.grupoCod) Total                FROM   _grupomodulo a, _modulos b,                         _opcoes_modulo c, _usuarios d,                        _tipo_permissao e                   WHERE  a.grupoCod         = b.grupoCod                        AND b.moduloCod    = c.moduloCod                          AND e.usuarioCod   = d.usuarioCod                         AND c.opcoesmoduloCod = e.opcoesmoduloCod                        AND b.moduloCod    = " . $moduloCod . "                         AND d.usuarioCod   = " . $_SESSION['usuarioCod'] . "                         AND a.grupoCod     = " . $grupoCod . "                        AND e.permissao    = 'S'";        $linhaTotal = $this->con->execRLinha($sql);        return $linhaTotal['Total'];    }    /**     * @abstract Retorna se existe algum submenu filho do modulo que o usuario tenha permissao     * @author Yuri Gauer Marques     */    protected function checaPermissaoMenuPai($moduloCod)    {        $sql = "SELECT a.moduloCod Total FROM _modulos a, _opcoes_modulo b, _tipo_permissao c                WHERE a.moduloReferente = " . $moduloCod . " AND                a.moduloCod = b.moduloCod AND                b.opcoesmoduloCod = c.opcoesmoduloCod AND                c.usuarioCod = " . $_SESSION['usuarioCod'] . "  AND                c.permissao = 'S' LIMIT 1";        $ocorrencias = $this->con->executar($sql);        $NumOcorrencias = $this->con->nLinhas($ocorrencias);        return $NumOcorrencias;    }}