<script setup>
import { ref } from 'vue';
const props = defineProps({Logo:String});
const companyLogo = ref(props.Logo ? props.Logo : '' );
const email 			= ref('');
const password 		= ref('');
const error 			= ref(false);

function submitLogin(){
	let data = new FormData();
	data.append('email', email.value);
	data.append('password', password.value);
	data.append('wptba_nonce',wptba_nonce);
	data.append('action','wptbaLogin');
	fetch(wptba_ajax_url,{
		method:'POST',
		body:data
	})
	.then(response => response.json())
	.then(response => {
		if(response == 0){
			error.value = true;
		}
		if(response != 0){
			//console.log(response);
			localStorage.setItem('jwt',response);
			getDarkMode()
			
		}
	})
	.catch(error => {
		console.log(error);
		});
}

function getDarkMode(){
	if(localStorage.getItem('darkMode')){
		//darkMode.value = localStorage.getItem('darkMode');
	}else{
		const data = new FormData();
		data.append('action', 'wptbaDownloadDarkMode');
		data.append('jwt',localStorage.getItem('jwt'));
		data.append('wptba_nonce',wptba_nonce);

		fetch(wptba_ajax_url,{
			method: 'POST',
			body: data
		})
		.then(response => response.json())
		.then(response =>{
			localStorage.setItem('darkMode',response);
			window.location.reload();
		})
		.catch(err=> console.log(err));

	}
}

</script>
<template>
	<div class="h-screen flex justify-center items-center  mx-auto ">
		<form class="container px-6 max-w-sm py-4 border border-gray-100  rounded-xl shadow-lg " @submit.prevent="submitLogin">	
			<div class="flex justify-center">
				<img 
					v-bind:src="companyLogo" 
					class="rounded-full border-8 "
				/>	
			</div>
			<label for="email" class="block text-base mb-1  text-gray-400 tracking-tight">Email</label>
			<input id="email" type="email"  placeholder="email"  class="w-full block px-4 py-2 border rounded mb-3 focus:outline-0  focus:border-blue-600" required v-model="email"/>
			<label for="pass" class="block text-base mb-1  text-gray-400 tracking-tight">Password</label>
			<input id="pass" type="password"  placeholder="password"  class="w-full block px-4 py-2 border rounded mb-3 focus:outline-0  focus:border-blue-600" required v-model="password"/>
			<div class="flex justify-center ">
				<p v-show="error" class="text-gray-400 text-base tracking-tight py-2 mb-1">User do no exists or Incorrect Password</p>
			</div>
			<div class="flex justify-between items-center">
				<div class="flex flex-row">
				<a href="#" @click.prevent="$emit('toggleComponent',1)" class="text-sm text-blue-600 font-semibold tracking-tight mr-3">Create Account</a>
				<a href="#" @click.prevent="$emit('toggleComponent',2)" class="text-sm text-blue-700 font-semibold tracking-tight">Forgot password?</a>
				</div>
				<button  class="bg-blue-600 rounded text-white px-6 py-2 border-0 font-semibold cursor-pointer">Login </button>

			</div>
		</form>
	</div>
</template>
