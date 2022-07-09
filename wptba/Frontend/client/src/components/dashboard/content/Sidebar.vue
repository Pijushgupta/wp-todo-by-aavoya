<template>
	<div class="w-2/5 md:w-3/12  2xl:w-2/12  border-r border-gray-200 dark:border-gray-800     dark:bg-gray-900 ">
		<div class="flex flex-col sidebar-posts-height relative">
			<div class="flex-grow overflow-y-auto tw-scroll-bar">
					<div v-if="posts != false" >
						<div 
						v-for="post in posts " 
						class="post-list flex justify-between items-center" 
						v-bind:key="post.id" > 
						
						<div @click="$emit('loadBody',post.id)" class="w-full">{{post.title}} </div>
						<div @click="$emit('deletePost',post.id)"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /> </svg></div>
						</div>
		
					</div>
					<div v-if="posts == false">
						<div class="text-gray-500 flex justify-center"> Loading ...</div>
					</div>
				
			</div>
			<div v-if="popUp" class=" mb-3 border border-gray-100 dark:border-gray-800 rounded bg-white dark:bg-gray-800 mx-3 p-3 flex flex-col absolute bottom-10 left-0 right-0  absolute shadow">
				<div class="w-full">
					<input type="text" v-model="postTitle" class="rounded w-full bg-gray-100 dark:bg-gray-900 dark:text-gray-300  py-2 px-2" placeholder="Boarad Name"/>
				</div>
				<div class="w-full flex justify-end mt-3">
					<button @click.prevent="addPost" class="px-5 py-2 bg-gray-100 dark:bg-gray-900   dark:text-gray-300 rounded">Create</button>
				</div>
			</div>
			<div 
			@click.prevent="showPopup" 
			class="mt-auto flex-shrink-0 bg-blue-700 dark:bg-blue-700 text-white  py-2 px-3 text-center cursor-pointer" 
			v-bind:class="popUp == true ? 'bg-red-700' : ''"
			>Create Board</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'Sidebar',
	props: {
		posts: [Array,Boolean],
	},
	data(){
		return {
			popUp: false,
			postTitle: null,
			
		}
	},
	methods: {
		 showPopup(){
			this.popUp = !this.popUp;
		},
		 addPost(){
			if(this.postTitle == null) return;
			this.popUp = false;
			this.$emit('addPost', this.postTitle);
			this.postTitle = null;
		},
	
	},
	

}
</script>
