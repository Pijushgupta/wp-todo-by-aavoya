<template>
<div class="flex justify-center py-8">
	<div class="container max-w-2xl border rounded-lg overflow-hidden ">
		<ul>
			<li class="border-b last:border-b-0 p-4">
				<div class="flex flex-row justify-between w-full items-center">
					<div >Secret Key for JWT </div>
					<div >
						<span class="px-2">{{key}}</span>
						<button class="border rounded px-4 py-2 bg-blue-500 text-white" @click.prevent="regenerateKey" >Regenerate</button>
					</div>
				</div>
			</li>
			<li class="border-b last:border-b-0 p-4">
				<div class="flex flex-row justify-between">
					<div>Auto logout user after</div>
					<div>
						<select v-model="autoLogOut" @change="setAutoLogOut">
							<option v-for="i in 365" :key="i" :value="i">{{i}} days</option>
						</select>
					
					</div>
				</div>
			</li>
			<li class="border-b last:border-b-0 p-4">
				<div class="flex flex-row justify-between">
					<div>Manually Approval of new Accounts</div>
					<div>
						<input type="checkbox" v-model="autoApproveUser" @change="setApproveUser"/>
					</div>
				</div>
			</li>
			
		</ul>
	</div>
</div>
</template>
<script setup>
import {ref, watch} from 'vue';
const key 				= ref('');
const autoLogOut	= ref('');
const autoApproveUser = ref(false);



 function getKey() {
	
	const data = new FormData()
	data.append('action', 'getKetKeyWptba');
	data.append('wptba_backend_nonce', wptba_backend_nonce);

	fetch(wptba_backend_url,{
		method: 'POST',
		body: data
	})
	.then(response => response.json())
	.then(response => {
		if(response != null ){
			key.value =  response;
		}
	})
	.catch(err => console.log(err));
	
}
function regenerateKey(){
		const data = new FormData()
		data.append('action', 'setKeyWptba');
		data.append('wptba_backend_nonce', wptba_backend_nonce);

		fetch(wptba_backend_url,{
			method: 'POST',
			body: data
		})
		.then(response => response.json())
		.then(response => {
			if(response == true){
				getKey();
			}
		})
		.catch(err => console.log(err));
}
function getAutoLogOut(){
	const data = new FormData()
	data.append('action', 'getAutoLogOutWptba');
	data.append('wptba_backend_nonce', wptba_backend_nonce);

	fetch(wptba_backend_url,{
		method: 'POST',
		body: data
	})
	.then(response => response.json())
	.then(response => {
		if(response != null ){
			autoLogOut.value =  response;	
		}
	})
	.catch(err => console.log(err));
}
function setAutoLogOut(){
	const data = new FormData()
	data.append('action', 'setAutoLogOutWptba');
	data.append('wptba_backend_nonce', wptba_backend_nonce);
	data.append('autoLogOut', autoLogOut.value);
	fetch(wptba_backend_url,{
		method: 'POST',
		body: data
	})
	.then(response => response.json())
	.then(response=>{
		console.log(response);
	})
	.catch(err => console.log(err));
}

function getApproveUser(){
	const data = new FormData();
	data.append('action', 'getAauWptba');
	data.append('wptba_backend_nonce', wptba_backend_nonce);

	fetch(wptba_backend_url,{
		method: 'POST',
		body: data
	})
	.then(response => response.json())
	.then(response => {
		if(response != null ){
			autoApproveUser.value =  response;	
		}
	})
	.catch(err => console.log(err));
}
function setApproveUser(){
	const data = new FormData()
	data.append('action', 'setAauWptba');
	data.append('wptba_backend_nonce', wptba_backend_nonce);
	data.append('autoApproveUser', autoApproveUser.value);

	fetch(wptba_backend_url,{
		method: 'POST',
		body: data
	})
	.then(response => response.json())
	.then(response => {
		//console.log(response);
	})
	.catch(err => console.log(err));
}


getKey();
getAutoLogOut();
getApproveUser();











</script>