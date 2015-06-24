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
/**
 * 
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 5/11/2014
 * @version 1.0
 * @copyright 2014
 * 
 * 
 * 
 */

namespace Zion\Exportacao;

class PDF {
    
    public function impressaoGridPDF($dados, $cssFile, $cssPath, $controller, $logo)
    {
        
        $titulo     = uniqid() .'_relatorio_'. MODULO .'_'. date('d-m-Y-H:i:s') .'.pdf';
        $nomeModulo = (new \Base\Sistema\Modulo\ModuloClass())->getDadosModulo(MODULO)['modulonomemenu'];

        try {

            if(\count($dados) < 1){
                throw new \Exception('Nenhum dado a ser exibido!');
            }

            include_once(SIS_FM_BASE . 'Lib\mPDF\mpdf.php');
            
            $stylesheet = $this->loadCss($cssFile, $cssPath);
            
            $dadosHtml = \json_decode($dados['retorno'], true);

            $html = $controller->layout()->render('impressao_grid_pdf.html.twig', [
                                                    'grid'              => ['retorno' => $dadosHtml],
                                                    'logo'              => $logo,
                                                    'modulo'            => $nomeModulo,
                                                    'dataRelatorio'     => date("d/m/Y \à\s H:i:s")
                                                   ]);

            $mpdf = new \mPDF();

            $mpdf->CurOrientation = "L";

            $mpdf->allow_charset_conversion = true;
            $mpdf->charset_in    = 'UTF-8';
            
            if(\strlen($dadosHtml['legenda']) > 5){
                $mpdf->SetHTMLFooter($dadosHtml['legenda']);
            } else {
                $mpdf->SetFooter('{PAGENO}');
            }
            
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            //exit('<style>'. $stylesheet .'</style>'. $html);
            $mpdf->Output($titulo, 'D');

            return $this->jsonSucesso('Arquivo gerado com sucesso!');

         } catch(Exception $e) {
            return $this->jsonErro('Erro ao gerar PDF!');
         }
    }
    
    private function loadCss($cssFile, $cssPath = false) 
    {        
        if($cssPath === false){
            $cssPath = \SIS_URL_DEFAULT_BASE . 'Tema/Vendor/Pixel/1.3.0/stylesheets/';
        }
        
        $files = \preg_replace('/\\n/', '', \file_get_contents($cssPath . $cssFile));
        $css = NULL;
   
        foreach (explode(';', $files) as $val) {

            $start = NULL;
            $end = NULL;

            \preg_match('/[url(\']{5}/', $val, $start, PREG_OFFSET_CAPTURE);
            \preg_match('/[\')]{2}/', $val, $end, PREG_OFFSET_CAPTURE);

            if (isset($val[0]) === false) {
                continue;
            }
            $file = \substr($val, ($start[0][1] + 5), -2);
            
            if (!preg_match('/[http\:\/\/]{7}|[https\:\/\/]{8}/', $file)) {
                $urlFile = $cssPath . $file;
            } else {
                $urlFile = $file;
            }
            
            $css .= \file_get_contents($urlFile);
        }
        return ($css);
    }

    public function imprimeRelatorioPDF($html, $css = false, $orientacao = false) {
        try {

            include_once(SIS_FM_BASE . 'Lib\mPDF\mpdf.php');
            $mpdf = new \mPDF();

            $mpdf->CurOrientation = $orientacao;

            $mpdf->allow_charset_conversion = true;
            $mpdf->charset_in = 'UTF-8';
            $stylesheet = $this->getCss();

            $mpdf->setFooter('{PAGENO}/{nbpg}');
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
            //$mpdf->Output(\uniqid() . '_' . \date('d/m/Y') . '.pdf', 'D');
            print '<style>\n' . $this->getCss() . '"\n</style>' . $html;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getCss() {
        return '
        .main {
            margin-left: 20px;
            width: 100%;
        }

        .main thead th {
            border: solid 1px #000;
            background-color: #CCC;
            text-align: center;
            padding: 5px;
        }
        td {
            text-align: center;
            padding: 5px;
        }
        .lineSpan {
            width: 100%;
            height: 25px;
            clear: both;
        }
        .lineContent {
            //Nada por eqto
        }

        .lineContent > td {
            text-align: right;
            width: 50%;
        }
        .lineContent td:last-child {
            text-align: left;
            width: 50%;
        }
        .lineTitle td{
            text-align: right;
        }';
    }

    public function imprimePDF($html, $tituloArquivo = NULL, $orientacao = NULL) {

        $titulo = (is_null($tituloArquivo) ? uniqid() . '_relatorio_' . date('d-m-Y-H:i:s') : $tituloArquivo) . '.pdf';
        $orientacao = (is_null($orientacao) ? 'P' : $orientacao);

        try {

            include_once(SIS_FM_BASE . 'Lib\mPDF\mpdf.php');
            $mpdf = new \mPDF();

            $mpdf->CurOrientation = $orientacao;

            $mpdf->allow_charset_conversion = true;
            $mpdf->charset_in = 'UTF-8';
            $stylesheet = $this->getEstiloRelatorio();

            $mpdf->setFooter('{PAGENO}/{nbpg}');
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html, 2);
//            $mpdf->Output($titulo, 'D');
print('<style type="text/css">'. $stylesheet .'</style>'. $html);
        } catch (Exception $e) {
            return false;
        }
    }

    private function getEstiloRelatorio() {

        $stylesheet = '
            th {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                background-color: #666666;
                color:#FFFFFF;
                font-size: 13px;
                height:30px;
            }
			tbody{
				margin-top:20px;
				border:1px solid #666666;
				border-bottom: none;
			}
			.table-bordered {
				margin-bottom:20px;
                width: 100%;
			 }
            .table-bordered tr{
                border:1px solid #666666;
                
			}
            td {
                border:1px solid #666666;
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 12px;
				text-align:center;
				height:25px;
            }
            .t12preto {
                font-family: Verdana, Arial, Helvetica, sans-serif;            
                font-size: 12px;
                color: #000000;            
                text-decoration: none;
                border:none;
            }
            .table-footer{
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 12px;
                text-decoration: none;
            }';

        return $stylesheet;
    }


}
