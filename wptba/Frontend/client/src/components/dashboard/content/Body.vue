<template>
	<div class="dark:bg-gray-900 w-full p-2 ">
		<div class="flex flex-row flex-wrap">
		<div v-for="(item, i) in board" :key="i" class="p-2">
			
				<div class="rounded-lg border border-gray-200 dark:border-gray-800 max-w-xs ">
					<div class="heading border-b border-gray-200 dark:border-gray-800 p-2 py-2 font-semibold text-gray-500 dark:text-gray-400 cursor-move flex justify-between items-center">
						<input type="text" v-model="board[i].title" class="dark:bg-gray-900 w-full font-semibold " />
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 cursor-pointer" @click="deleteList(i)" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /> </svg>
					</div>
					<div class="p-2 pb-1 ">
						<form class="flex flex-row ">
						<input 
						type="text" 
						v-bind:id="i" 
						class="border border-gray-200 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400 px-2 py-2 rounded-l focus:outline-none w-full"
						/>
						<button  @click.prevent="addItemToArray(i)" class="border bg-blue-700 text-white py-1 px-3 border-blue-700 rounded-r">add</button>
						</form>
					</div>
					
					
					
					<draggable 
					v-model="board[i].data"
					:group="draggbaleGroup"
					animation=100
					tag="ul"
					itemKey="key"
					class="p-2"
					ghostClass="py-6"
					>
					
						<template #item="{element,index}">
							<li 
							class="border text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-800 px-2 py-2 mb-1 last:mb-0 rounded cursor-move flex justify-between items-center">
							<span style="max-width:13rem;">{{element}}</span> 
							<svg xmlns="http://www.w3.org/2000/svg" @click="deleteItem(i,index)" class="h-4 w-4 cursor-pointer  " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /> </svg></li>		
							
						</template>

					</draggable>

				</div>
			
		</div>
		</div>
		<div class="absolute bottom-5  right-5 ">
			<button class="bg-blue-700 px-4 py-4 rounded-full " @click="createNewList"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /> </svg></button>
		</div>
	</div>
</template>
<script>

import draggable from 'vuedraggable'
export default{
	name:'Body',
	components:{
		draggable
	},
	props: {
		postsToLoad: [String,Number,Boolean],
	},
	data(){
		return{
			board:false,
			createDraggable:0,
			draggbaleGroup:'draggable-group',
			userCred : localStorage.getItem('jwt'),
		}
	},
	methods:{
		test(item){
			console.log(item);
		},
		getMeta(){
			const data =  new FormData();
			data.append('jwt', this.userCred);
			data.append('action', 'wptbaGetPostMeta');
			data.append('wptba_nonce', wptba_nonce);
			data.append('post_id', this.postsToLoad);
			fetch(wptba_ajax_url,{
				method: 'POST',
				body: data
			})
			.then(response => response.json())
			.then(response => {
				if (response.todos) {
					this.board = response.todos;
				}
				
				
				})
			.catch(error => console.log(error));
		},
		sendMeta(meta){
			const data =  new FormData();
			data.append('jwt', this.userCred);
			data.append('action', 'wptbaSetPostMeta');
			data.append('wptba_nonce', wptba_nonce);
			data.append('post_id', this.postsToLoad);
			data.append('meta', JSON.stringify(meta));

			fetch(wptba_ajax_url,{
				method: 'POST',
				body: data
			})

			.then(response => response.json())
			.catch(error => console.log(error));
		},
		addItemToArray(index){
			var input = document.getElementById(index).value;
			if(input != ''){
				this.board[index].data.unshift(input);
				document.getElementById(index).value = '';
			}
		},
		deleteItem(index, i) {
			
			this.board[index].data.splice(i,1);
		},
		createNewList() {
			this.board.push({
				data: [],
				title: 'New List'
			});
		},
		deleteList(index) {
			if (!window.confirm('Are you sure you want to delete this list?')) return;
			this.board.splice(index,1);
		},
	},
	mounted(){
			this.getMeta();
	},
	watch:{
		board:{
			handler:  function(newVal,oldVal){
				if(oldVal == false) return;
				this.sendMeta(newVal);
			},
			deep: true
		},
		postsToLoad(newVal, oldVal) {
			if (this.postsToLoad != null) {
				this.getMeta();
			}
			if (this.postsToLoad == null && this.board != false) {
				window.location.reload();
			}
			
		}
	}
}
</script>