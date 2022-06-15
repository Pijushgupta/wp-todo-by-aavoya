<template>
<div class="p-4 ">
	<div class=" border rounded-lg">
		<div v-if="userData != false && userData != null" class="flex flex-col">
			<div v-for="user in userData" :key="user['id']" class="flex flex-row justify-between items-center p-4 border-b last:border-b-0">
				<div class="flex flex-col ">
					<div>{{user['name']}}</div>
					<div>{{user['email']}}</div>
				</div>
				<div>
					<button class="border rounded px-4 py-2 bg-blue-500 text-white" @click="addUser(user['id'])">Activate</button>
				</div>
			</div>
			
		</div>
		<div v-if="userData == null" class="flex justify-center items-center">
			<div class="p-4">No new Account Request</div>
		</div>
	</div>
</div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
const userData = ref(false);

const  pendingUser = () => { 
	const data = new FormData();
	data.append('action', 'wptbaGetPendingUsers');
	data.append('wptba_backend_nonce', wptba_backend_nonce);

	fetch(wptba_backend_url,{
		method: 'POST',
		body: data
	})
		.then(response => response.json())
		.then(response => {
			if (response == 'null') {
				response = null;
			}
			userData.value = response;
			console.log(response);
			
		})
		.catch(err => console.log(err));
}

const addUser = (postId) => {
	const data = new FormData();
	data.append('action', 'wptbaPostToUser');
	data.append('wptba_backend_nonce', wptba_backend_nonce);
	data.append('postId', postId);

	fetch(wptba_backend_url, {
		method: 'POST',
		body: data
	})
		.then(response => response.json())
		.then(response => { 
			if(response == 'success'){
				pendingUser();
			}
		 })
		.catch(err => console.log(err));
}

onMounted(() => {
	pendingUser();
});
</script>