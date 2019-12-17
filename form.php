<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<form action="createcharge.php" method="post" id="pay" class="" name="pay" >
                <div class="col-sm-12">
                  <h2>Pago</h2>
                  <p>Realice su pago y espere los productos en la comodidad de su hogar.</p>
                  <div class="form-group">
               
                    <label for="email" class="control-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="test_user_19653727@testuser.com"  placeholder="Su email"/>
                  </div>
                  <div class="form-group">
                <label for="cardNumber" class="control-label">Número tarjeta de crédito (sin espacios):</label>
                <input type="text" id="cardNumber" data-checkout="cardNumber" placeholder="Ejemplo 4509953566233704" onselectstart="return false" onpaste="return true" onCopy="return false" onCut="return false" onDrag="return false" class="form-control"  onDrop="return false" autocomplete=off />
            </div>
            <div class="form-group">
                <label for="securityCode" class="control-label">Código de seguridad:</label>
                <input type="text" id="securityCode" data-checkout="securityCode" placeholder="123" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" class="form-control"  autocomplete=off />
            </div>
            <div class="form-group">
                <label for="cardExpirationMonth" class="control-label">Mes de expiración:</label>
                <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" placeholder="11" onselectstart="return false" class="form-control"  onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
            </div>
            <div class="form-group">
                <label for="cardExpirationYear" class="control-label">Año de expiración:</label>
                <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" placeholder="2025" onselectstart="return false" class="form-control"  onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
            </div>
            <div class="form-group">
                <label for="cardholderName" class="control-label">Nombre en la tarjeta:</label>
                <input type="text" id="cardholderName" class="form-control"  data-checkout="cardholderName" placeholder="APRO" />
            </div>
            <div class="form-group">
                <label for="docType" class="control-label">Tipo de documento:</label>
                <select id="docType" class="form-control"  data-checkout="docType"></select>
            </div>
            <div class="form-group">
                <label for="docNumber" class="control-label">Número de documento:</label>
                <input id="docNumber" type="text"  class="form-control"  data-checkout="docNumber">
            </div>
            
            <div class="form-group">
                <label for="installments" class="control-label">Cuotas:</label>
               <select id="installments" class="form-control" name="installments">
               </select>
            </div>
          
        <input type="hidden" name="paymentMethodId" />
        <input type="submit" style="text-align: center;" class="btn btn-primary" value="Pay!" />
                </div>
                </form>
<script>
    window.Mercadopago.setPublishableKey("<ACCESS_TOKEN>");
    window.Mercadopago.getIdentificationTypes();
    function addEvent(to, type, fn){ 
        if(document.addEventListener){
            to.addEventListener(type, fn, false);
        } else if(document.attachEvent){
            to.attachEvent('on'+type, fn);
        } else {
            to['on'+type] = fn;
        }  
    }; 

addEvent(document.querySelector('#cardNumber'), 'keyup', guessingPaymentMethod);
addEvent(document.querySelector('#cardNumber'), 'change', guessingPaymentMethod);

function getBin() {
  const cardnumber = document.getElementById("cardNumber");
  return cardnumber.value.substring(0,6);
};

function guessingPaymentMethod(event) {
    var bin = getBin();

    if (event.type == "keyup") {
        if (bin.length >= 6) {
            window.Mercadopago.getPaymentMethod({
                "bin": bin
            }, setPaymentMethodInfo);
        }
    } else {
        setTimeout(function() {
            if (bin.length >= 6) {
                window.Mercadopago.getPaymentMethod({
                    "bin": bin
                }, setPaymentMethodInfo);
            }
        }, 100);
    }
};

function setPaymentMethodInfo(status, response) {
    if (status == 200) {
        const paymentMethodElement = document.querySelector('input[name=paymentMethodId]');

        if (paymentMethodElement) {
            paymentMethodElement.value = response[0].id;
        } else {
            const input = document.createElement('input');
            input.setAttribute('name', 'paymentMethodId');
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', response[0].id);     

            form.appendChild(input);
        }

        Mercadopago.getInstallments({
            "bin": getBin(),
            "amount": parseFloat(document.querySelector('#amount').value),
        }, setInstallmentInfo);

    } else {
        alert(`Error en el método de pago: ${response}`);  
    }
};
doSubmit = false;
addEvent(document.querySelector('#pay'), 'submit', doPay);
function doPay(event){
    event.preventDefault();
    if(!doSubmit){
        var $form = document.querySelector('#pay');

        window.Mercadopago.createToken($form, sdkResponseHandler); // The function "sdkResponseHandler" is defined below

        return false;
    }
};

function sdkResponseHandler(status, response) {
    if (status != 200 && status != 201) {
        alert("Por favor verifique los campos ingresados");
    }else{
        var form = document.querySelector('#pay');
        var card = document.createElement('input');
        card.setAttribute('name', 'token');
        card.setAttribute('type', 'hidden');
        card.setAttribute('value', response.id);
        form.appendChild(card);
        doSubmit=true;
        form.submit();
    }
};
function setInstallmentInfo(status, response) {
        var selectorInstallments = document.querySelector("#installments"),
        fragment = document.createDocumentFragment();
        selectorInstallments.options.length = 0;

        if (response.length > 0) {
            var option = new Option("Seleccione...", '-1'),
            payerCosts = response[0].payer_costs;
            fragment.appendChild(option);

            for (var i = 0; i < payerCosts.length; i++) {
                fragment.appendChild(new Option(payerCosts[i].recommended_message, payerCosts[i].installments));
            }

            selectorInstallments.appendChild(fragment);
            selectorInstallments.removeAttribute('disabled');
        }
    };

</script>
</body>
</html>
