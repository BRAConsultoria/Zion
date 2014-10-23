<?phpnamespace Zion\Paginacao;class Parametros{    public static $parametros = [];    public static function limpaParametros()    {        self::$parametros = [];    }    public static function setParametros($metodo, $valores)    {        switch (strtoupper($metodo)) {            case "GET":                foreach ($valores as $valor) {                    $getValor = filter_input(INPUT_GET, $valor);                    if ($getValor != '') {                        self::$parametros[$valor] = $getValor;                    }                }                break;            case "POST":                foreach ($valores as $valor) {                    $postValor = filter_input(INPUT_POST, $valor);                    if ($postValor != '') {                        self::$parametros[$valor] = $postValor;                    }                }                break;            case "REQUEST":                foreach ($valores as $valor) {                    $requestValor = filter_input(INPUT_REQUEST, $valor);                    if ($requestValor != '') {                        self::$parametros[$valor] = $requestValor;                    }                }                break;            case "FULL":                foreach ($valores as $valor => $conteudo) {                    self::$parametros[$valor] = $conteudo;                }                break;        }    }    public static function getQueryString()    {        if (is_array(self::$parametros) and ! empty(self::$parametros)) {                        $query = '';                        foreach (self::$parametros as $campo => $valor) {                $query .= "&" . $campo . "=" . urlencode($valor);            }            return substr($query, 1);        } else {            return "";        }    }    public static function addQueryString($queryAtual, $campoValor)    {        $novaQuery = '';                if (!empty($queryAtual)) {            $vCampos = explode("&", $queryAtual);            if (is_array($vCampos)) {                foreach ($vCampos as $campoJunto) {                    $vetJunto = explode("=", $campoJunto);                    $arrayRetorno[$vetJunto[0]] = $vetJunto[1];                }                foreach ($campoValor as $campo => $valor) {                    $arrayRetorno[$campo] = $valor;                }                foreach ($arrayRetorno as $campo => $valor) {                    $novaQuery.="&" . $campo . "=" . $valor;                }                return substr($novaQuery, 1);            }        } else {            foreach ($campoValor as $campo => $valor) {                $novaQuery.= "&" . $campo . "=" . $valor;            }            return substr($novaQuery, 1);        }    }}