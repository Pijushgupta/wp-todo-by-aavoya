<template>
<div>
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
import { ref, onMounted , defineEmits} from 'vue';
const props = defineProps({
	postToLoad:[Number,Boolean]
})
const emit = defineEmits({
	logout: String
});
const id  = ref(props.postToLoad);
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
			if (res == 1 || res == '1') {
				users.value = [];
				return;
			}
			if (res == 0 || res == '0') {
				emit('logout');
				return;
			}
			users.value = res;
			console.log(users.value);
			
			
		})
		.catch(err => {
			console.log(err);
		});
}
/**
 * Getting all the tags from the server related to current post
 */
function getTags() {
	if (userCred.value == false) return;
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
			

			if (res == 0 || res == '0') {	
				emit('logout');
				return;
			}

			if (res == null || res == 'null') {
				tags.value = [];
				return;
			}

			tags.value = res;
			console.log(tags.value);
			getTaggedUsers();
			
			
		})
		.catch(err => {
			console.log(err);
		});
}

/**
 * Converting Tag id to user and storing 
 */
function getTaggedUsers() {
	if (Array.isArray(tags.value) == false || tags.length == 0) return;
	taggedUsers.value = [];
	for (let i = 0; i < tags.value.length; i ++ ){
		for (let j = 0; j < users.value.length; j ++ ){
			if (users.value[j].id == tags.value[i].id) {
				taggedUsers.value.push(users.value[j]);
				break;
			}
		}
	}	
	getTaggableUser();


}

/**
 * creating list of taggable users 
 * taggable users: users that are not in the tagged used list
 */
function getTaggableUser() {
	if (Array.isArray(users.value) == false || users.length == 0) return;

	if (Array.isArray(tags.value) == false || tags.length == 0) {
		taggableUsers.value = users.value;
		return;
	}

	taggableUsers.value = [];

		for(let i = 0; i < users.value.length; i ++){ 
			for (let j = 0; j < tags.value.length; j++) { 
				if (tags.value[j].id !=  users.value[i].id) {
						taggableUsers.value.push(users.value[i]);
					}
			}
	}
	console.log(taggableUsers.value);
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
	//TODO: remove the tag from the server first then remove from the client 

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
				tags.value = tags.value.filter((tag) => {
					if (tag.id != tagId) {
						return tag;
					}
				});
				getTaggedUsers();
				return;
			}

			if (response == 0 || response == '0') {
				emit('logout');
				return;
			}
			
			
		})
		.catch(error => console.log(error));
	

	 
	
	
	
}





onMounted(() => {
	if(id.value == false) return;

	getAllUser();
	getTags();
	
	
});
</script>