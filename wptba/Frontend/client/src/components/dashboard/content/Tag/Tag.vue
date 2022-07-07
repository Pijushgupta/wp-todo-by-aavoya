<template>
<div>
	<div v-show="openAddTagDialog" class="absolute inset-0 w-full flex justify-center items-center z-10  backdrop-blur ">
				<div class="w-72 md:w-1/5 flex flex-col border border-gray-200 dark:border-gray-800 rounded-lg dark:bg-gray-900 bg-gray-50">
					<!-- Header -->
					<div class="flex flex-row justify-between items-center border-b border-gray-200 dark:border-gray-800 px-4 py-2 font-semibold text-gray-500 dark:text-gray-400">
						<div class="w-1/2">Share with</div>
						<div class="w-1/2 flex justify-end"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 cursor-pointer" viewBox="0 0 20 20" fill="currentColor" @click="openAddTagDialog = !openAddTagDialog"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /> </svg></div>
					</div>
				
					<!-- List Area -->
					<div v-if="taggableUsers.length > 0" class="px-4 py-2  flex flex-row items-center flex-start flex-wrap justify-center">
						<div 
						v-for="taggableUser in taggableUsers" 
						:key="taggableUser.id" 

						@mouseenter="showtooltip(taggableUser.id,true)"
						@mouseleave="showtooltip(taggableUser.id,false)"
						@click="addTag(taggableUser.id)"

						class="bg-blue-500  rounded-full text-white w-12  h-12  cursor-pointer m-2 flex justify-center items-center relative">
							{{createInitials(taggableUser.name)}}
							<!-- tooltip -->
							<div v-bind:id="taggableUser.id+'tt'" class="absolute text-xs -top-7 bg-gray-100 shadow-sm dark:bg-gray-800 text-gray-600 dark:text-gray-400 min-w-max p-1 rounded hidden backdrop-blur">
								{{taggableUser.name}}
							</div>
							<!-- tooltip ends -->
						</div>
					</div>
					<div v-if="taggableUsers.length <= 0" class="px-4 py-2  flex flex-row items-center flex-start flex-wrap justify-center text-gray-500 dark:text-gray-400">
						No other users 
					</div>
				</div>
		</div>
		<div class="tag-area  rounded-lg flex flex-row justify-end p-2">
				<div  v-if="taggedUsers.length != 0" class="flex flex-row">
					<div v-for="taggedUser in taggedUsers" v-bind:key="taggedUser.id" class="bg-blue-500  rounded-full text-white w-10  h-10  flex items-center justify-center cursor-pointer mr-2 relative">
							<svg @click="removeTag(taggedUser.id)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute -top-1 -right-1 cursor-pointer bg-blue-500 rounded-full" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /> </svg>
							{{createInitials(taggedUser.name)}}
					</div>
				</div>

				<div @click="openAddTagDialog = !openAddTagDialog" class="bg-blue-700  rounded-full text-white w-10  h-10  flex items-center justify-center cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline fill-white" viewBox="0 0 20 20" fill="currentColor"> <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" /> </svg></div>
				
			
		</div>
		</div>
</template>
<script setup>

import { ref, onMounted , watch, computed } from 'vue';
const props = defineProps({
	postToLoad:[Number,Boolean]
})
const emit = defineEmits({
	logout: String
});
const id = computed(() => {
	return props.postToLoad;
});
const openAddTagDialog = ref(false);
const userCred = localStorage.getItem('jwt');

const users = ref([]);
const tags = ref([]);

const taggedUsers = ref([]);
const taggableUsers = ref([]);





/**
 * Gettign all the todoers from the server, except the current user
 */
function getAllUser() {
	if (!userCred) return;
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
			
			if (res === 0 || res === '0') {
				
				emit('logout');
				return;
			}

			if (res == 1 || res == '1') {
				users.value = [];

			} else {
				users.value = res;
			
			}
			
			getTags();
			
			
			
		})
		.catch(err => {
			console.log(err);
		});
}
/**
 * Getting all the tags from the server related to current post
 */
function getTags() {
	if (!userCred) return;

	const data = new FormData();
	data.append('jwt', userCred);
	data.append('wptba_nonce', wptba_nonce);
	data.append('action', 'wptbaGetTags');
	data.append('post_id', id.value);

	fetch(wptba_ajax_url, {
		method: 'POST',
		body: data
	})
		.then(res => res.json())
		.then(res => {
			
			
			if (res === 0 || res === '0') {	
				
				emit('logout');
				
				return;
			}

			if (res === null || res === 'null') {
				tags.value = [];
				
			} else {
				tags.value = res;
			}

			
			getTaggedUsers();
			
			getTaggableUser();
		})
		.catch(err => {
			console.log(err);
		});
}

/**
 * Converting Tag id to user and storing 
 */
function getTaggedUsers() {
	taggedUsers.value = [];
	
	if (Array.isArray(tags.value) == false || tags.value.length == 0) return;
	
	for (let i = 0; i < tags.value.length; i ++ ){
		for (let j = 0; j < users.value.length; j ++ ){
			if (users.value[j].id == tags.value[i].id) {
				taggedUsers.value.push(users.value[j]);
				break;
			}
		}
	}	
	//console.log(users.value);

	
	
	
}

/**
 * creating list of taggable users 
 * taggable users: users that are not in the tagged used list
 */
function getTaggableUser() {
	
	if (Array.isArray(users.value) == false || users.value.length == 0) {
		//TODO: return a message that, there is no other user in the server.
		return;
	} 
	
	if (Array.isArray(tags.value) == false || tags.value.length == 0) {
		
		taggableUsers.value = users.value;
		

	} else { 
		
		
		taggableUsers.value = users.value.filter((user) => {
			let t = 0;
			for (let i = 0; i < tags.value.length; i++){
				if (user.id == tags.value[i].id) {
					t++;
				}
			}
			if (t == 0) {
				return user;
			}
		});

		

	
		
	}
	
	
	

}

/**
 * Convert full name to initial
 */
function createInitials(name) {
	let nameArray = name.split(" ");
	let initial;
	if (nameArray.length >= 2) {
		initial = nameArray[0].charAt(0).toUpperCase() + nameArray[nameArray.length - 1].charAt(0).toUpperCase();
	} else {
		initial = nameArray[0].charAt(0).toUpperCase() + nameArray[0].charAt(1).toUpperCase()
	}

	return initial;

}

/**
 * remove specific from tag list
 */
function removeTag(tagId) {
	if (!userCred) return;

	const data = new FormData();
	data.append('tagId', tagId);
	data.append('postId', id.value)
	data.append('wptba_nonce', wptba_nonce);
	data.append('jwt', userCred);
	data.append('action', 'wptbaRemoveTag');

	fetch(wptba_ajax_url, {
		method: 'POST',
		body: data
	})
		.then(response => response.json())
		.then(response => {
			
			if (response == true || response == 'true') {
				
				getTags();
				
				return;
			}

			if (response == 0 || response == '0') {
				emit('logout');
				return;
			}
			
		})
		.catch(error => console.log(error));	
}

/**
 * add to server then update list by calling getTag method
 */
function addTag(tagId) {
	if (!userCred) return;

	const data = new FormData();
	data.append('jwt', userCred);
	data.append('wptba_nonce', wptba_nonce);
	data.append('action', 'wptbaAddTag');

	data.append('tagId', tagId);
	data.append('postId', id.value);

	fetch(wptba_ajax_url, {
		method: 'POST',
		body: data
	})
		.then(response => response.json())
		.then(response => { 
			if (response == '0' || response == 0) {
				emit('logout');
				return;
			}

			if (response == null || response == 'null') {
				return;
			}

			
			getTags();

		})
		.catch(err => console.log(err));
	
}

function showtooltip(id,status) {

	let tt = document.getElementById(id + 'tt');
	if (status === true) { 
		
		tt.classList.remove('hidden');
	}

	if (status === false) { 
		tt.classList.add('hidden');
	}
	
}

watch(id, () => {
	getAllUser();
})



onMounted(() => {
	
	getAllUser();
});

</script>