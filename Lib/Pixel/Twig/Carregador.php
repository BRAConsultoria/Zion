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

namespace Pixel\Twig;

use Zion\Arquivo\ManipulaDiretorio;

class Carregador
{

    private $twig;
    private $loader;
    private $caminhos;
    private $conf;
    protected $dir;

    /**
     * @param $namespace optional namespace a partir do qual sera montado um
     *                            caminho para ser adicionado no \Twig_Loader
     * @param $paths     optional caminhos adicionais para serem carregados no
     *                            \Twig_Loader
     */
    public function __construct($namespace = '', $paths = [])
    {
        $this->caminhos = $paths;
        $this->dir = new ManipulaDiretorio();

        if ($namespace) {
            $dirnamespace = $this->interpretaNamespace($namespace) . '/Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/views';

            if ($this->dir->eDiretorio($dirnamespace)) {
                $this->caminhos[] = $this->interpretaNamespace($namespace) . '/Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/views';
            }
        }

        $caminhoBase = \SIS_DIR_BASE . 'Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/views';
        $caminhoProjeto = \SIS_DIR_DEFAULT_BASE . 'Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/views';

        if ($this->dir->eDiretorio($caminhoBase)) {
            $this->caminhos[] = $caminhoBase;
        }

        if ($this->dir->eDiretorio($caminhoProjeto)) {
            $this->caminhos[] = $caminhoProjeto;
        }

        $this->loader = new \Twig_Loader_Filesystem($this->caminhos);

        if (\SIS_RELEASE === 'Developer') {
            $this->conf['debug'] = true;
        } else {
            $this->conf['debug'] = false;
            $this->conf['cache'] = \SIS_DIR_BASE . 'Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/cache';
        }

        $this->twig = new \Twig_Environment($this->loader, $this->conf);

        $urlBase = new \Twig_SimpleFunction('urlBase', function ($url) {
            return \SIS_URL_BASE . $url;
        });

        $urlBaseTema = new \Twig_SimpleFunction('urlBaseTema', function ($url) {
            return \SIS_URL_DEFAULT_BASE . 'Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/' . $url;
        });

        $urlFramework = new \Twig_SimpleFunction('urlFramework', function ($url) {
            return \SIS_URL_FM_BASE . $url;
        });

        $urlBaseSite = new \Twig_SimpleFunction('urlBaseSite', function ($url) {
            return \SIS_URL_BASE_SITE . $url;
        });

        $urlBaseStorage = new \Twig_SimpleFunction('urlBaseStorage', function ($url) {
            return \SIS_URL_BASE_STORAGE . $url;
        });

        $this->twig->addFunction($urlBase);
        $this->twig->addFunction($urlBaseTema);
        $this->twig->addFunction($urlFramework);
        $this->twig->addFunction($urlBaseSite);
        $this->twig->addFunction($urlBaseStorage);
        $this->trataLegenda();
    }

    /**
     * Retorna a instancia do Twig
     *
     * @return \Twig_Environment
     */
    public function twig()
    {
        return $this->twig;
    }

    /**
     * Retorna o Loader do Twig
     *
     * @return \Twig_Loader_Filesystem
     */
    public function loader()
    {
        return $this->loader;
    }

    /**
     * Renderiza um template do twig com dados opcionais, caso o template nao
     * exista, o Twig lancara um exception, caso aconteca um erro de compilacao,
     * outra exception sera lancada e por fim, caso haja um erro em tempo de
     * execucao, outra exception sera lancada.
     *
     * @param string $template o nome do template a ser renderizado
     * @param array  $dados    os dados para serem disponibilizados  para o tpl
     * @throws \Twig_Error_Loader  Quando um template nao pode ser encontrado
     * @throws \Twig_Error_Syntax  Quando ha um erro durante a compilacao
     * @throws \Twig_Error_Runtime Quando ha um erro em tempo de execucao
     * @return string O Template renderizado
     */
    public function render($template, $dados = [])
    {
        return $this->twig->render($template, $dados);
    }

    /**
     * Adiciona um caminho absoluto no comeco do \Twig_Loader
     *
     * @param string $caminho
     * @return self
     */
    public function setCaminhoAbsolutoAntes($caminho)
    {
        $this->loader->prependPath($caminho);
        return $this;
    }

    /**
     * Adiciona um caminho no inicio dos caminhos ja existentes e carregados no
     * \Twig_Loader
     *
     * @param string $caminho o caminho a ser adicionado
     * @return self
     */
    public function setCaminhoAntes($caminho)
    {
        $caminhoCompleto = \SIS_DIR_BASE . $caminho;

        \array_unshift($this->caminhos, $caminhoCompleto);

        $this->loader->prependPath($caminhoCompleto);
        return $this;
    }

    /**
     * Adiciona um caminho no final dos caminhos ja existentes e carregados no
     * \Twig_Loader
     *
     * @param string $caminho o caminho a ser adicionado
     * @return self
     */
    public function setCaminhoDepois($caminho)
    {
        $caminhoCompleto = \SIS_DIR_BASE . $caminho;

        $this->caminhos[] = $caminhoCompleto;

        $this->loader->addPath($caminhoCompleto);
        return $this;
    }

    /**
     * A partir de um namespace, monta um caminho usando o diretorio base do
     * projeto definido na constante SIS_DIR_BASE. Caso o namespace seja passado
     * em branco, uma string vazia e retornada.
     *
     * @param string $namespace
     * @return string
     */
    private function interpretaNamespace($namespace)
    {
        if ($namespace !== '') {

            $caminho = \SIS_DIR_BASE . \str_replace(\SIS_ID_NAMESPACE_PROJETO . '\\', '', $namespace);

            return $this->dir->padronizaDiretorio($caminho, '/');
        }

        return $namespace;
    }

    /**
     * Adiciona configuracao ao Twig, o argumento tem que ser um array onde a
     * chave sera a chave de configuracao e o valor, o valor da configuracao.
     *
     * $carregador = new Carregador();
     * $carregador->setConf(['debug' => true]);
     *
     * @param array $conf
     * @return self
     */
    public function setConf($conf)
    {
        if (\is_array($conf)) {
            $key = \key($conf);
            $this->conf[$key] = $conf[$key];
        }
        return $this;
    }

    /**
     * Adiciona ao Twig uma funcao para tratar legendas
     *
     * @return void
     */
    private function trataLegenda()
    {
        $this->twig()->addFunction(new \Twig_SimpleFunction('trataLegenda', function ($legenda) {
            if (\strlen($legenda) > 10) {
                return \preg_replace([
                            '/class="table-footer"/',
                            '/<div class="col-sm-1">/',
                            '|</div>|',
                            '/btn-block/'
                                ], [
                            '',
                            '',
                            '',
                            ''
                                ], $legenda) . '</div></div>';
            } else {
                return NULL;
            }
        }));
    }

    /**
     * Retorna os caminhos adicionados ao \Twig_Loader
     *
     * @return array lista de caminhos
     */
    public function getCaminhos()
    {
        return $this->caminhos;
    }

}
