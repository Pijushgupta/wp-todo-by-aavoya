<template>
<div v-show="openAddTagDialog" class="absolute inset-0 w-full flex justify-center items-center z-10  backdrop-blur ">
				<div class="w-72 md:w-1/5 flex flex-col border border-gray-200 dark:border-gray-800 rounded-lg dark:bg-gray-900 bg-gray-50">
					<!-- Header -->
					<div class="flex flex-row justify-between items-center border-b border-gray-200 dark:border-gray-800 px-4 py-2 font-semibold text-gray-500 dark:text-gray-400">
						<div class="w-1/2">Share with</div>
						<div class="w-1/2 flex justify-end"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 cursor-pointer" viewBox="0 0 20 20" fill="currentColor" @click="openAddTagDialog = !openAddTagDialog"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /> </svg></div>
					</div>
					<!-- Search Area -->
					<div class="px-4 py-2 ">
						<input class="w-full border border-gray-200 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400 px-2 py-2 rounded focus:outline-none text-gray-500 dark:text-gray-400"/>
					</div>
					<!-- List Area -->
					<div class="px-4 py-2  ">

					</div>
				</div>
		</div>
		<div class="tag-area  rounded-lg flex flex-row justify-end p-2">
			<div class="relative">
				<div @click="openAddTagDialog = !openAddTagDialog" class="bg-blue-700  rounded-full text-white w-10  h-10  flex items-center justify-center cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline fill-white" viewBox="0 0 20 20" fill="currentColor"> <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" /> </svg></div>
				
			</div>
			
		</div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
const props = defineProps({
	postToLoad:[Number,Boolean]
})
const id  = ref(props.postToLoad);
const openAddTagDialog = ref(false);
const userCred = localStorage.getItem('jwt') ;
const users = ref(false);



function getAllUser() {
	if (userCred.value == false) return;
	const data = new FormData();
	data.append('jwt', userCred);
	data.append('wptba_nonce', wptba_nonce);
	data.append('action', 'wptbaGetAllUsers');

	fetch(wptba_ajax_url, {
		method: 'POST',
		body: data
	})
		.then(res => res.json())
		.then(res => {
			console.log(res);
		})
		.catch(err => {
			console.log(err);
		});
	
}

onMounted(() => {
	if(id.value == false) return;

	getAllUser();
	
});
</script>