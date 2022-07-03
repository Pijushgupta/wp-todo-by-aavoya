<template>
<div class="flex justify-center py-8">
	<div class="container max-w-2xl border rounded-lg overflow-hidden ">
		<ul>
			<li class="border-b last:border-b-0 p-4">
				<div class="flex flex-row justify-between w-full items-center">
					<div >Logo</div>
					<div >
						
						<div class="rounded-full w-14 h-14 bg-gray-400 flex justify-center items-center text-gray-200 cursor-pointer" v-on:click="logoPopup = !logoPopup">
							<!-- if no photo -->
							<div v-if="attachmentId == false">
								Photo
							</div>
							<!-- ends -->
							<!-- if photo available -->
							<div v-if="attachmentId != false && attachmentUrl" class="rounded-full overflow-hidden">
								<img v-bind:src="attachmentUrl" />
							</div>
							<!-- ends -->
						</div>
						
							
							
							<!-- popup -->
							<div v-if="logoPopup == true">
								 <Media @logoPopup = "logoPopup = !logoPopup" v-bind:post_id=" parseInt(attachmentId) " @imageUrl='updateImageUrl'/>
							</div>
							<!-- popup ends -->
						
					</div>
				</div>
			</li>
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
						<input type="checkbox" v-model="autoApproveUser" @click="setApproveUser"/>
					</div>
				</div>
			</li>
			
		</ul>
	</div>
</div>
</template>
<script setup>
import { ref, watch, onMounted } from 'vue';
import Media from './media/Media.vue';

const key 				= ref('');
const autoLogOut	= ref('');
const autoApproveUser = ref(false);
const logoPopup = ref(false);
const attachmentId = ref(false);
const attachmentUrl = ref(false);


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

function setApproveUser() {
	autoApproveUser.value = !autoApproveUser.value;
	console.log('setting', autoApproveUser.value);
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
		console.log('getting',response);
	})
	.catch(err => console.log(err));
}

function getAttachmentId() {
	const data = new FormData();
	data.append('action', 'wptbaGetAttchmentId');
	data.append('wptba_backend_nonce', wptba_backend_nonce);
	fetch(wptba_backend_url, {
		method: 'POST',
		body: data
	})
		.then(response => response.json())
		.then(response => { 
			if (response == false || response == 'false') {
				return;
			}
			attachmentId.value = response;
			getAttachment();
		})
		.catch(err => console.log(err));
}

/**
 * getting image url from the attachment ID
 */
function getAttachment() {
	if (attachmentId.value == false) { return}

	const data = new FormData();
	data.append('attchmentId', attachmentId.value);
	data.append('action', 'wptbaGetAttachment');
	data.append('wptba_backend_nonce', wptba_backend_nonce);
	fetch(wptba_backend_url, {
		'method': 'POST',
		body: data
	})
	.then(r => r.json())
	.then(r => {
		attachmentUrl.value = r;
	})
	.catch(err =>console.log(err));
	
}

function updateImageUrl(url) {
	attachmentUrl.value = url;
	
}

onMounted(() => {
	getKey();
	getAutoLogOut();
	getApproveUser();
	getAttachmentId();
});












</script>