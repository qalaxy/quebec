/*
 * Javascript to handle error forms
 */
 
function showMessageInput(responsibility){
	
	let msg_div = document.getElementById('message');
	
	if(responsibility == '0'){
		msg_div.style.display = 'block';
		let input = '<label class="w3-text-dark-gray">Notification message<span class="w3-text-red">*</span></label>';
		input += '<textarea class="w3-input w3-border-dark-gray w3-border" placeholder="Write a notification message to persons giving corrective action to the error"';
		input += ' name="notification_message" rows="2"></textarea>';
		
		msg_div.children[0].innerHTML = input;
	}else if(responsibility == '1'){
		msg_div.style.display = 'none';
		msg_div.children[0].innerHTML = null;
	}
	console.log(msg_div.children[0]);
	
}

function loadAffectedProduct(affected_product, url){
	let show = document.getElementById('show');
	let paragraph = '';
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);
	xhr.send();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			let data = JSON.parse(xhr.responseText);
			show.children[0].children[0].children[1].innerHTML = 'Product affected by error: '+data.error;
			paragraph += '<p><strong>Product:</strong> '+data.product;
			paragraph += '</p><p><strong>Identification:</strong> '+data.identification;
			paragraph += '</p><p><strong>Added by:</strong> '+data.user;
			paragraph += '</p><p><strong>Date added:</strong> '+data.created_at+'</p>';
			show.children[0].children[1].innerHTML = paragraph;
			show.children[0].children[2].children[0].children[0].children[0].setAttribute('autofocus', true);
			console.log(show);
			show.style.display = 'block';
		}
	}
}

function deleteAffectedProduct(product, url){
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);
	xhr.send();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("delete").innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
		}
	}
}

function deleteErrorCorrection(error, url){
	let xhr = new XMLHttpRequest();
	xhr.open("GET", url);
	xhr.send();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("delete").innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
		}
	} 
}

function validateErrorSerachForm(url){
	
	let str = {"error_search_data": 
					{	
						"number":document.getElementById("error_search_number").value, 
						"station":document.getElementById("error_search_station").value,
						"func":document.getElementById("error_search_function").value,
						"originator":document.getElementById("error_search_originator").value,
						"error_from":document.getElementById("error_search_error_from").value,
						"error_to":document.getElementById("error_search_error_to").value,
						"correction_from":document.getElementById("error_search_correction_from").value,
						"correction_to":document.getElementById("error_search_correction_to").value
					}
				};

	str = JSON.stringify(str);

	let xhr = new XMLHttpRequest();
	xhr.open("GET", url+"/"+str);
	xhr.send();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){

			let data = JSON.parse(xhr.response);

			if(data.number){
				displayErrorMessage(document.getElementById("error_search_number").parentElement, data.number);
			}else{
				removeErrorMessage(document.getElementById("error_search_number").parentElement);
			}

			if(data.station){
				displayErrorMessage(document.getElementById("error_search_station").parentElement, data.station);
			}else{
				removeErrorMessage(document.getElementById("error_search_station").parentElement);
			}

			if(data.func){
				displayErrorMessage(document.getElementById("error_search_function").parentElement, data.func);
			}else{
				removeErrorMessage(document.getElementById("error_search_function").parentElement);
			}

			if(data.originator){
				displayErrorMessage(document.getElementById("error_search_originator").parentElement, data.originator);
			}else{
				removeErrorMessage(document.getElementById("error_search_originator").parentElement);
			}

			if(data.error_from){
				displayErrorMessage(document.getElementById("error_search_error_from").parentElement, data.error_from);
			}else{
				removeErrorMessage(document.getElementById("error_search_error_from").parentElement);
			}

			if(data.error_to){
				displayErrorMessage(document.getElementById("error_search_error_to").parentElement, data.error_to);
			}else{
				removeErrorMessage(document.getElementById("error_search_error_to").parentElement);
			}

			if(data.correction_from){
				displayErrorMessage(document.getElementById("error_search_correction_from").parentElement, data.correction_from);
			}else{
				removeErrorMessage(document.getElementById("error_search_correction_from").parentElement);
			}

			if(data.correction_to){
				displayErrorMessage(document.getElementById("error_search_correction_to").parentElement, data.correction_to);
			}else{
				removeErrorMessage(document.getElementById("error_search_correction_to").parentElement);
			}
			
			document.getElementById("error_search_submit").disabled = data.status;
						
		}
	}
}

function displayErrorMessage(elememt, value){
	elememt.children[1].setAttribute("class", "w3-input w3-border w3-border-red");
	elememt.children[2].setAttribute("class", "w3-text-red w3-small");
	elememt.children[2].innerHTML = value;
}

function removeErrorMessage(element){
	element.children[1].setAttribute("class", "w3-input w3-border w3-border-dark-gray");
	element.children[2].innerHTML = '&nbsp;';
}

function deleteOriginatorReaction(url){
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", url);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("delete").innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
		}
	}
}

function deleteSupervisorReaction(url){
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", url);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("delete").innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
		}
	}
}