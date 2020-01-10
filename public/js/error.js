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