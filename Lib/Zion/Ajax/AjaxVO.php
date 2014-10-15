<?phpnamespace Zion\Ajax;class AjaxVO{    private $url;    private $accepts;    private $async;    private $beforeSend;    private $cache;    private $complete;    private $contents;    private $contentType;    private $context;    private $converters;    private $crossDomain;    private $data;    private $dataFilter;    private $dataType;    private $error;    private $global;    private $headers;    private $ifModified;    private $isLocal;    private $jsonp;    private $jsonpCallback;    private $mimeType;    private $password;    private $processData;    private $script;    public function getUrl()    {        return $this->url;    }    public function setUrl($url)    {        $this->url = $url;        return $this;    }    public function getAccepts()    {        return $this->accepts;    }    public function setAccepts($accepts)    {        $this->accepts = $accepts;        return $this;    }    public function getAsync()    {        return $this->async;    }    public function setAsync($async)    {        $this->async = $async;        return $this;    }    public function getBeforeSend()    {        return $this->beforeSend;    }    public function setBeforeSend($beforeSend)    {        $this->beforeSend = $beforeSend;        return $this;    }    public function getCache()    {        return $this->cache;    }    public function setCache($cache)    {        $this->cache = $cache;        return $this;    }    public function getComplete()    {        return $this->complete;    }    public function setComplete($complete)    {        $this->complete = $complete;        return $this;    }    public function getContents()    {        return $this->contents;    }    public function setContents($contents)    {        $this->contents = $contents;        return $this;    }    public function getContentType()    {        return $this->contentType;    }    public function setContentType($contentType)    {        $this->contentType = $contentType;        return $this;    }    public function getContext()    {        return $this->context;    }    public function setContext($context)    {        $this->context = $context;        return $this;    }    public function getConverters()    {        return $this->converters;    }    public function setConverters($converters)    {        $this->converters = $converters;        return $this;    }    public function getCrossDomain()    {        return $this->crossDomain;           }    public function setCrossDomain($crossDomain)    {        $this->crossDomain = $crossDomain;        return $this;    }    public function getData()    {        return $this->data;    }    public function setData($data)    {        $this->data = $data;        return $this;    }    public function getDataFilter()    {        return $this->dataFilter;    }    public function setDataFilter($dataFilter)    {        $this->dataFilter = $dataFilter;        return $this;    }    public function getDataType()    {        return $this->dataType;    }    public function setDataType($dataType)    {        $this->dataType = $dataType;        return $this;    }    public function getError()    {        return $this->error;    }    public function setError($error)    {        $this->error = $error;        return $this;    }    public function getGlobal()    {        return $this->global;    }    public function setGlobal($global)    {        $this->global = $global;        return $this;    }    public function getHeaders()    {        return $this->headers;    }    public function setHeaders($headers)    {        $this->headers = $headers;        return $this;    }    public function getIfModified()    {        return $this->ifModified;    }    public function setIfModified($ifModified)    {        $this->ifModified = $ifModified;        return $this;    }    public function getIsLocal()    {        return $this->isLocal;    }    public function setIsLocal($isLocal)    {        $this->isLocal = $isLocal;        return $this;    }    public function getJsonp()    {        return $this->jsonp;    }    public function setJsonp($jsonp)    {        $this->jsonp = $jsonp;        return $this;    }    public function getJsonpCallback()    {        return $this->jsonpCallback;    }    public function setJsonpCallback($jsonpCallback)    {        $this->jsonpCallback = $jsonpCallback;        return $this;    }    public function getMimeType()    {        return $this->mimeType;    }    public function setMimeType($mimeType)    {        $this->mimeType = $mimeType;        return $this;    }    public function getPassword()    {        return $this->password;    }    public function setPassword($password)    {        $this->password = $password;        return $this;    }    public function getProcessData()    {        return $this->processData;    }    public function setProcessData($processData)    {        $this->processData = $processData;        return $this;    }    public function getScript()    {        return $this->script;    }    public function setScript($script)    {        $this->script = $script;        return $this;    }}