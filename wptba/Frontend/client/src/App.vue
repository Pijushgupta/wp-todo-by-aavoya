<script setup>
import {ref, watch , onMounted} from 'vue';
import Login from './components/authentication/Login.vue';
import Register from './components/authentication/Register.vue';
import ForgotPassword from './components/authentication/Forgot.vue';
import Dashboard from './components/dashboard/Dashboard.vue';

/**
 * dealUser
 * value = 0 : Loading Login Component
 * value = 1 : Loading Register Component
 * value = 2 : Loading Forgot Password Component
 * Value = 3 : Loading Dashboard Component
 */
const dealUser = ref(null);

/**
 * jwt is to hold jwt token from localStorage
 */
const jwt = ref('');



const logo = ref(false);




const getlogo = () => {
	const data = new FormData();

	data.append('wptba_nonce', wptba_nonce);
	data.append('action', 'wptbaGetLogo');

	fetch(wptba_ajax_url, {
		method: 'POST',
		body:data
	})
		.then(res => res.json())
		.then(res => {
			logo.value = res;
			
		})
	.catch(err=>console.log(err));

}


/**
 * This function is responsible for Loading proper
 * login template based on cookie stored in localStorage
 * "Immediately Loading Login form if no JWT found"
 * @param {string} jwt
 * @return {void}
 */
if(!localStorage.getItem('jwt')){

	/**
	 * Loading Login Component
	 */
	dealUser.value = 0;
	
	/**
	 * Its to remove the previous users DarkMode setting from Local Storage 
	 */
	if(localStorage.getItem('darkMode')){
		localStorage.removeItem('darkMode');
	}

}else{
	/**
	 * Loading Dashboard Component
	 */
	dealUser.value = 3;
	
	/**
	 * Loading JWT from Local Storage to jwt variable
	 */
	jwt.value = localStorage.getItem('jwt');


}
function toggleComponent(value) {
	if (value == 0) {
		dealUser.value = 0;
	} else if (value == 1) {
		dealUser.value = 1;
	} else if (value == 2) {
		dealUser.value = 2;
	} else if (value == 3) {
		dealUser.value = 3;
	}
}
function logout(){
	localStorage.removeItem('jwt');
	localStorage.removeItem('darkMode');
	dealUser.value = 0;
}

const fixWordpressHtmlMargin = () => {
	document.querySelector('html').style.setProperty('margin-top', '0px', 'important');
}

onMounted(() => {
	getlogo();
	fixWordpressHtmlMargin();
});
</script>

<template>
<div>
	<Login 
	v-if="dealUser == 0 && logo != false" 
	@toggleComponent="toggleComponent" 
	@logout="logout"
	v-bind:Logo="logo"
	/>
	<Register 
	v-if="dealUser == 1 && logo != false" 
	@toggleComponent="toggleComponent" 
	v-bind:Logo="logo"
	/>
	<ForgotPassword
		v-if="dealUser == 2 && logo != false"
		@toggleComponent="toggleComponent" 
		v-bind:Logo="logo"
	/>
	<Dashboard
	v-if="dealUser == 3 "
	@logout="logout"

	/>
</div>
</template>