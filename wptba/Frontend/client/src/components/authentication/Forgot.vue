<script setup>
import { ref } from 'vue';
const props = defineProps({Logo:String});
const companyLogo = ref(props.Logo ? props.Logo : '' );
const email = ref('');
const error = ref(false);

function resetPass(){
	if (email.value == '') return;
	const data = new FormData();
	data.append('action', 'wptbaResetPassword');
	data.append('wptba_nonce', wptba_nonce);
	data.append('email', email.value);

	fetch(wptba_ajax_url, {
		method: 'POST',
		body: data
	})
		.then(response => response.json())
		.then(response => {
			/**
			 * for nonce failure handling
			 */
			if (response == 0 || response == '0') {
				error.value = 'Email id not registered with us';
			}

			/**
			 * for success handling
			 */
			if (response == 1 || response == '1') { 
				error.value = 'Password reset link sent to your email';
			}


		})
		.catch(err => console.log(err));
}
</script>
<template>
	<div class="h-screen flex justify-center items-center  mx-auto ">
		<form class="container px-6 max-w-sm py-4 border border-gray-100  rounded-xl shadow-lg " @submit.prevent="resetPass">	
			<div class="flex justify-center">
				<img 
					v-bind:src="companyLogo" 
					class="rounded-full border-8 "
				/>	
			</div>
			<label for="email" class="block text-base mb-1  text-gray-400 tracking-tight">Email</label>
			<input id="email" type="email"  placeholder="email"  class="w-full block px-4 py-2 border rounded mb-3 focus:outline-0  focus:border-blue-600" required v-model="email"/>
			<div class="flex justify-center ">
				<p v-show="error" class="text-gray-400 text-base tracking-tight py-2 mb-1">{{error}}</p>
			</div>
			<div class="flex justify-between items-center">
				<div class="flex flex-row">
				<a href="#" @click.prevent="$emit('toggleComponent',1)" class="text-sm text-blue-600 font-semibold tracking-tight mr-3">Create Account</a>
				<a href="#" @click.prevent="$emit('toggleComponent',0)" class="text-sm text-blue-700 font-semibold tracking-tight">Login</a>
				</div>
				<button  class="bg-blue-600 rounded text-white px-6 py-2 border-0 font-semibold cursor-pointer">Reset Password</button>

			</div>
		</form>
	</div>
</template>
