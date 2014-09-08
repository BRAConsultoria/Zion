/*********************************************
**                                          **
**   Fun��es de Valida��o Por Pablo Vanni   **
**                                          **
*********************************************/

//Vazio
function vazio(Valor, Identifica){
	$Valor = $.trim(Valor);
	if($Valor == '') { alert("O Campo "+Identifica+" n�o pode ser vazio!"); return false; } return true;
}

//Tamanho Minimo
function tMinimo(Valor, Min, Identifica){
	if(Min > 0 ) { if(Valor.length < Min){ alert("O Campo "+Identifica+" deve ter no minimo "+Min+" Caracteres"); return false; }} return true;	
}

//Tamanho M�ximo
function tMaximo(Valor, Max, Identifica){
	if(Max > 0 ) { if(Valor.length > Max){ alert("O Campo "+Identifica+" deve ter no m�ximo "+Max+" Caracteres"); return false; }} return true;
}

//TValor Minimo
function vMinimo(Valor, VMin, Identifica){
	if(VMin > 0 )  { if(Valor < VMin){ alert("O Campo "+Identifica+" deve ter valor minimo de "+VMin); return false; }} return true;
}

//Valor Minimo Data
function vMinimoData(Valor, VMin, DataAtual,  Identifica){
	if(VMin > 0 )  { if(Valor < VMin){ alert("O Campo "+Identifica+" n�o pode conter data menor que "+DataAtual); return false; }} return true;
}

//Valor M�ximo
function vMaximo(Valor, VMax, Identifica){
	if(VMax > 0 )  { if(Valor > VMax){ alert("O Campo "+Identifica+" deve ter valor m�ximo de "+VMax); return false; }} return true;
}

//Valor M�ximo Data
function vMaximoData(Valor, VMax, DataAtual, Identifica){
	if(VMax > 0 )  { if(Valor > VMax){ alert("O Campo "+Identifica+" n�o pode conter data maior que "+DataAtual); return false; }} return true;
}

function inverteData(Data){
	try { var ArrayData = Data.split("/"); var ValorData = ArrayData[2]+ArrayData[1]+ArrayData[0];	return ValorData;}catch(e) { return false; }
}

//Data Minimo
function dataMinima(Valor, VMin, Identifica){
	var VMinInv = inverteData(VMin); var Invertida = inverteData(Valor); if(Invertida == false  || VMinInv == false) return false; else return vMinimoData(Invertida, VMinInv, VMin, Identifica);
}

//Data M�xima
function dataMaxima(Valor, VMax, Identifica){
	var VMaxInv = inverteData(VMax); var Invertida = inverteData(Valor); if(Invertida == false || VMaxInv == false) return false; else return vMaximoData(Invertida, VMaxInv, VMax, Identifica); 
}

//Valor Inteiro
function vInteiro(Valor, Identifica){
	if(isNaN(Valor))  { alert("O Campo "+Identifica+" n�o � um n�mero inteiro!"); return false; } return true;	
}

//Valida��o CPF
function cpf(Valor){
	var i = 0; var n_checked = 0; var cpf = Valor;	if (cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999") return false; soma = 0;	for (i=0; i<9; i++)	soma += parseInt(cpf.charAt(i))*(10-i);	resto = 11-(soma%11); if (resto == 10 || resto == 11) resto = 0; if (resto != parseInt(cpf.charAt(9))) return false; soma = 0; for (i=0; i<10; i++) soma += parseInt(cpf.charAt(i))*(11-i); resto = 11-(soma%11); if (resto == 10 || resto == 11) resto = 0; if (resto != parseInt(cpf.charAt(10))) return false; return true;
}

//CEP
function cep(cep){
	var regexp=/\d{5}-?\d{3}/; if(cep.search(regexp) == -1) return false; else return true;
}

//Email
function vemail(email){
	var exclude=/[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/; var check=/@[\w\-]+\./; var checkend=/\.[a-zA-Z]{2,3}$/; if(((email.search(exclude) != -1)||(email.search(check)) == -1)||(email.search(checkend) == -1)) return false; else return true;
}
//######################################################################################################################

//Texto
function validaTexto(Valor, Max, Min, Identifica){
	if(!vazio(Valor, Identifica)) return false; if(!tMinimo(Valor, Min, Identifica)) return false; if(!tMaximo(Valor, Max, Identifica)) return false; return true;
} 

//Inteiro
function validaInteiro(Valor, Max, Min, VMax, VMin, Identifica){
	if(!vazio(Valor, Identifica)) return false; if(!vInteiro(Valor, Identifica)) return false; if(!tMinimo(Valor, Min, Identifica)) return false; if(!tMaximo(Valor, Max, Identifica)) return false; if(!vMinimo(Valor, VMin, Identifica)) return false; if(!vMaximo(Valor, VMax, Identifica)) return false; return true;
}

//Float
function validaFloat(Valor, Max, Min, VMax, VMin, Identifica){
	VMax = c2float(interpretaEval(VMax)); VMin = c2float(interpretaEval(VMin)); Max  = c2float(interpretaEval(Max)); Min  = c2float(interpretaEval(Min)); if(!vazio(Valor, Identifica)){ return false; } else { var NovoValor = c2float(Valor); NovoValor+="";  return validaInteiro(NovoValor, Max, Min, VMax, VMin, Identifica); }
}

//Data
function validaData(Valor, VMax, VMin, Identifica){
	VMax = interpretaEval(VMax); VMin = interpretaEval(VMin); var ExpReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}$/; if(!vazio(Valor, Identifica)) return false; if(!ExpReg.test(Valor)) { alert("O Campo "+Identifica+" n�o possui uma data v�lida"); return false; } if(VMin != 0) { if(!dataMinima(Valor, VMin, Identifica)) return false; } if(VMax != 0) { if(!dataMaxima(Valor, VMax, Identifica)) return false; } return true;
}

//Iterpreta a nescessidade de um eval - Para Datas
function interpretaEval(Valor){
	Valor+=""; var V = Valor.substr(0,1); if(isNaN(V)){ eval('var Retorno = d.'+Valor+'.value;'); if(Retorno == 'undefined' || Retorno == "") { Retorno = "0"; }} else { var Retorno = Valor;} return Retorno;
}

//CPF
function validaCPF(Valor, Identifica){
	if(!vazio(Valor, Identifica)) { alert("CPF inv�lido!"); return false; }  if(Valor.length != 14 ) { alert("O Campo "+Identifica+" deve ter 14 Caracteres"); return false; }try{ var Vet1  = Valor.split(".");	var Vet2  = Vet1[2].split("-");	Valor = Vet1[0]+Vet1[1]+Vet2[0]+Vet2[1]; } catch(e) { alert("CPF inv�lido!"); return false; }if(cpf(Valor)) return true; else { alert("CPF inv�lido!"); return false; }
}

//Email
function validaEmail(email){
	if(!vemail(email)) { alert('Email Inv�lido!'); return false; } else return true;
}

//Fone
function validaFone(Fone){
	if(Fone.length < 1){ alert('Telefone Inv�lido'); return false;} else { return true; }
}

//Converte para float
function c2float(valor) { 
	if(valor != ""){ var tamanho = valor.length; if(!isNaN(valor)) return parseFloat(valor);  if(tamanho >= 4){var final = valor.substr((tamanho - 2),tamanho); var v = valor.replace(".",""); v = v.replace(",",""); v = v.substr(0,(v.length - 2)); return parseFloat(v+"."+final); } else { var v = valor.replace(",","."); return parseFloat(v); }} else { return "0"; }
}