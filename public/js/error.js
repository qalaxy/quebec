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