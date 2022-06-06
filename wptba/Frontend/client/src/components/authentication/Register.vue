<script setup>
import { ref } from 'vue';
const props = defineProps({Logo:String});
const companyLogo = ref(props.Logo ? props.Logo : '' );
const name				= ref('');
const email				= ref('');
const username		= ref('');

function submitRegister(){

}

function getAvilableUsername(){

}

</script>
<template>
<div class="h-screen flex justify-center items-center  mx-auto ">
		<form class="container px-6 max-w-sm py-4 border border-gray-100  rounded-xl shadow-lg " @submit.prevent="submitRegister">	
			<div class="flex justify-center">
				<img 
					v-bind:src="companyLogo" 
					class="rounded-full border-8 "
				/>	
			</div>
			<label for="name" class="block text-base mb-1  text-gray-400 tracking-tight">Name</label>
			<input id="name" type="text"  placeholder="name"  class="w-full block px-4 py-2 border rounded focus:outline-0  focus:border-blue-600 mb-3" required v-model="name"/>
			<label for="email" class="block text-base mb-1  text-gray-400 tracking-tight">Email</label>
			<input id="email" type="email"  placeholder="email"  class="w-full block px-4 py-2 border rounded focus:outline-0  focus:border-blue-600 mb-3" required v-model="email"/>
			<label for="username" class="block text-base mb-1  text-gray-400 tracking-tight">Username</label>
			<div class="flex flex-row items-center mb-3 ">
				<input  id="username" type="text"  placeholder="username"  class="w-full block px-4 py-2  rounded-l border border-r-0 focus:outline-0  focus:border-blue-600   " required v-model="username"/>
				<button @click.prevent="getAvilableUsername" class=" border border-blue-600 border-r-0 bg-blue-700 py-2 px-6 text-white rounded-r font-semibold">Check</button>
			</div>
			

			<div class="flex justify-center ">
				<p v-show="error" class="text-gray-400 text-base tracking-tight py-2 mb-1">User do no exists or Incorrect Password</p>
			</div>
			<div class="flex justify-between items-center">
				<div class="flex flex-row">
				<a href="#" @click.prevent="$emit('toggleComponent',0)" class="text-sm text-blue-600 font-semibold tracking-tight mr-3">Login</a>
				<a href="#" @click.prevent="$emit('toggleComponent',2)" class="text-sm text-blue-600 font-semibold tracking-tight">Forgot password?</a>
				</div>
				<button  class="bg-blue-600 rounded text-white px-6 py-2 border-0 font-semibold cursor-pointer">Register</button>

			</div>
		</form>
	</div>
</template>