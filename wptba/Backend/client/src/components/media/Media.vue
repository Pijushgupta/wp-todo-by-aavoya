<template>
	<div class="absolute inset-0 flex justify-center items-center backdrop-blur z-10">			
		
		<div  class="w-1/3 flex flex-col  rounded-3xl  bg-white p-3  shadow-lg relative "  @dragenter.prevent="" @dragover.prevent="" @drop.prevent="handleDrop">
			<div class="close absolute -top-2 -right-2 border bg-white shadow-sm rounded-full cursor-pointer" @click="$emit('logoPopup')">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /> </svg>
			</div>
			<div class="border border-4 rounded-2xl  border-dashed border-gray-300 flex flex-col justify-center items-center">
				<svg v-if="attachmentUrl == false" xmlns="http://www.w3.org/2000/svg" class="h-36 w-36 stroke-gray-300 mt-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"> <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /> </svg>
				<img v-if="attachmentUrl" v-bind:src="attachmentUrl" class="rounded-full">
				<span class="text-2xl font-semibold my-2 text-gray-300">Drop files to upload</span>
				<span class="mb-2 text-gray-300">or</span>
				<input id="realuploadInput" type="file" class="hidden" accept="image/*" @change="handleDrop">
				<button class="font-semibold mb-3 border px-6 py-2 rounded text-white bg-blue-500 cursor-pointer" @click="fileSelecter">Select file</button>
			</div>
		</div>
		
	</div>
</template>
<script setup>
import { ref,onMounted} from 'vue';
const props = defineProps({
	post_id: [Number, Boolean]
});	

const attachmentId = ref(false);
const attachmentUrl = ref(false);

const emit = defineEmits(['imageUrl']);


function handleDrop(e) {
	let inputFile = e.target.files || e.dataTransfer.files;
	inputFile = inputFile[0];

	/**
	 * It will prevent others types of file from being send
	 * Client side Only
	 */ 
	if (inputFile.type.search('image') == -1) {
		return;
	}

	const data = new FormData();
	data.append('logo', inputFile);
	data.append('action', 'wptbaUploadImage');
	data.append('wptba_backend_nonce', wptba_backend_nonce);
	
	
	const options = {
		method: 'POST',
		body: data
	}

	fetch(wptba_backend_url, options)
		.then(r => r.json())
		.then(r => {
			if (r == 0 || r == '0') {
				alert('Not an Image File');
				return;
			}

			if (r == 1 || r == '1') {
				alert('Upload Error');
				return;
			}

			attachmentUrl.value = r;
			emit('imageUrl', r);

		 })
		.catch(err => console.log(err));

}
function fileSelecter() {
	document.querySelector('#realuploadInput').click();
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

onMounted(() => {
	if (props.post_id) {
		attachmentId.value = props.post_id;
	}
	getAttachment();
})
</script>
