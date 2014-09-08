﻿<?php
/*
 * Classe de utilidades - Usanda na manutenção básica
 * Criada em 01-09-2010
 * Pablo Vanni
 */

include_once($_SESSION['FMBase'].'funcoes_php.class.php');
include_once($_SESSION['FMBase'].'parse_sql.class.php');

class Util
{
    /*
     * Metodo que retorna um array com o nome dos campos de formulários
     * retorna Array
     */
    public function getParametrosForm(FormCampos $ObjForm)
    {
        //Incia Variavel que receberá os campos
        $ArrayCampos = array();

        //Recuperando Array de Campos
        $ArrayForm = $ObjForm->getBufferCFG();

        //Monta Array de Retotno
        if(is_array($ArrayForm))
        {
            foreach($ArrayForm as $CFG)
            {
                $ArrayCampos[] = $CFG['Nome'];
            }
        }

        return $ArrayCampos;
    }

    /*
     * Metodo que processa e retorna partes de uma clausula SQL de acordo com os filtros
     * returna String
     */
    public function getSqlFiltro(Filtrar $Fil, FormCampos $ObjForm)
    {
        //Incia Variavel que receberá as instruções Sql
        $Sql = "";

        //Recuperando Array de Campos
        $ArrayForm = $ObjForm->getBufferCFG();

        //Monta Sql de Retotno
        if(is_array($ArrayForm))
        {
            foreach($ArrayForm as $CFG)
            {
                $Alias = ($CFG['AliasSql'] == '') ? "" : $CFG['AliasSql'].".";

                $Sql .= $Fil->getStringSql($CFG['Nome'],$Alias.$CFG['Nome'],$CFG['ProcessarComo']);
            }
        }

        return $Sql;
    }

    /*
     * Receber uma string de parametros e o objetoform e processa-os retornando um vetor com os paremtros prontos para a inserção
     * retorna Array
     */
    public function getSqlInsertUpdate(FormCampos $ObjForm, $Sql)
    {
        //Instancia Classe de Parse SQL
        $ParseSql = new ParseSql();

        //Tipo de Interpretação
        $TipoSql = strtoupper(substr(trim($Sql),0,6));

        if($TipoSql == "INSERT" or $TipoSql == "REPLAC")
        {
            $ArrayParametros = $ParseSql->getAtributosInsert($Sql);

        }
        elseif($TipoSql == "UPDATE")
        {
            $ArrayParametros = $ParseSql->getAtributosUpdate($Sql);
        }

        //Incia Variavel que receberá o Sql
        $ArraySql = array();

        //Recuperando Array de Campos
        $ArrayForm = $ObjForm->getBufferCFG();
        
        if(is_array($ArrayParametros))
        {
            $ArrayParametros = array_map("trim",$ArrayParametros);

            foreach($ArrayParametros as $NomeParametro)
            {
                if(array_key_exists($NomeParametro,$ArrayForm))
                {
                    $DadosCampo = $ArrayForm[$NomeParametro];

                    $PodeSerVazio = $DadosCampo['Obrigatorio'] === true ? false : true;
                    $ArraySql[] = $ObjForm->get($NomeParametro,$PodeSerVazio,$DadosCampo['ProcessarComo']);
                }
                else
                {                   
                    $Valor = $ObjForm->get($NomeParametro, true);

                    if($Valor.'' != '')
                    {
                        $ArraySql[] = $Valor;
                    }
                    else
                    {
                        $ArraySql[] = 'NULL';
                    }
                }
            }
        }        

        return $ArraySql;
    }


    /*
     * Converte parametros de arrays para uma super global
     * $ParametrosForm = Array de Nomes de Campos
     * $ParametrosSql  = Array de Valores de Campos
     * $Chave          = Chave Identificadora
     * $Metodo         = POST, GET
     * return void
     */
    public function getParametrosMetodo($ParametrosForm, $ParametrosSql, $Chave, $Metodo)
    {
        //Instancia Funções PHP
        $FPHP = new FuncoesPHP();

        //Cria Array de Processamento
        $ArrayProcessamento = array();

        //Cria Array Para Converssão em Super Global
        foreach($ParametrosForm as $Valor)
        {
            if($Valor == "Id")
            {
                $ArrayProcessamento[$Valor] = $ParametrosSql[$Chave];
            }
            else
            {
                $ArrayProcessamento[$Valor] = $ParametrosSql[$Valor];
            }
        }

        //Extrai Variaveis para o metodo desejado
        $FPHP->extractVar($ArrayProcessamento, $Metodo);
    }

    /*
     * Metodo responsável por montar arquivo de tamplate
     */
    public function montaTpl(FormCampos $ObjForm, array $CamposForm)
    {
        //BufferHtml
        $BufferHtml = "";

        //Recuperando Array de Campos
        $ArrayForm = $ObjForm->getBufferCFG();

        foreach($CamposForm as $Campo=>$ConteudoCampo)
        {
            if($Campo != "Id") $BufferHtml.= '<tr><td width="170" align="right" class="textoForm">'.$ArrayForm[$Campo]['Identifica'].':</td><td>'.$ConteudoCampo.'</td></tr>'."\n";
        }

        return $BufferHtml;
    }
}//fim classe
