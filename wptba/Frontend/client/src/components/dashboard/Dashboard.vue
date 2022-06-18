<template>
	<div 
		v-bind:class="darkMode == true ? 'dark' : ''"
	>
		<Header 
		v-if="userProfile" 
		@tooglemode="toogleDarkMode" 
		@changePass="changePassword = true"
		@logout="$emit('logout')"
		v-bind:userProfile="userProfile" 
		v-bind:darkMode="darkMode"
		/>
		<div class="w-full flex flex-row">
			<Sidebar 
			v-if="posts != false" 
			v-bind:posts="posts"
			@addPost="addPost"
			@deletePost="deletePost"
			@loadBody="loadBody"
			/>
			
			<Body 
			v-if="postsToLoad != false && postsToLoad != 'undefined'"
			v-bind:postsToLoad="postsToLoad"
			v-bind:changePassword="changePassword"
			@disablePassDiag="disablePassDiag"
			@logout="$emit('logout')"
			/>
		</div>
		
	</div>
</template>
<script>
import Header from './content/Header.vue';
import Sidebar from './content/Sidebar.vue';
import Body from './content/Body.vue';

export default{
	name:'Dashboard',
	components:{
		Header,
		Sidebar,
		Body,
	},
	data: ()=>{
		return {
			userProfile:'',
			posts:false,
			postsToLoad:false,
			userProfile:false,
			darkMode:'',
			userCred: localStorage.getItem('jwt'),
			changePassword:false,
		}
	},

	methods:{
		getUserDetails(){
			const data = new FormData();
			data.append('jwt', this.userCred);
			data.append('action', 'wptbaGetUserDetails');
			data.append('wptba_nonce', wptba_nonce);
			fetch(wptba_ajax_url,{
				method: 'POST',
				body: data
			})
			.then(response => response.json())
			.then(response => {
				if (response == 0) {
					this.$emit('logout');
				}
				this.userProfile = response
				this.getPosts();
			})
			.catch(error => console.log(error));
		},
		getPosts(){
			const data = new FormData();
			data.append('jwt', this.userCred);
			data.append('action', 'wptbaGetPosts');
			data.append('wptba_nonce', wptba_nonce);
			fetch(wptba_ajax_url,{
				method: 'POST',
				body: data

			})
			.then(response => response.json())
			.then(response => {
				if(response == 'null'){
					this.posts = null
					this.postsToLoad = null
				}else{
					this.posts = response;	
					this.postsToLoad = response[0].id;
				}
			})
			.catch(err => console.log(err));
		},
		addPost(title){
			const data = new FormData();
			data.append('title', title);
			data.append('jwt', this.userCred);
			data.append('wptba_nonce',wptba_nonce );
			data.append('action', 'wptbaAddPost');

			fetch(wptba_ajax_url, {
				method: 'POST',
				body: data
			})
			.then(response => response.json())
			.then(response => {
				
				if(this.posts == null){
					this.posts = [] ;	
				}
				
				this.posts.unshift(response);
				this.loadBody(response.id);
				
			})
			.catch(error => console.log('Error:', error));
		},
		deletePost(postId) {
			if (!window.confirm('Are you sure?')) return;
		
			const data = new FormData();
			data.append('action', 'wptbaDeletePost');
			data.append('jwt', this.userCred);
			data.append('wptba_nonce', wptba_nonce);
			data.append('post_id', postId);

			fetch(wptba_ajax_url, {
				method: 'POST',
				body: data
			})
				.then(response => response.json())
				.then(response => {
					if (response) { 
						this.posts = this.posts.filter(post => post.id !== postId);	
						if (this.posts && this.posts[0]) { 
							this.postsToLoad = this.posts[0].id;
						} else {
							this.postsToLoad = null; 
							this.posts = null;
						}
										
						
					}
				})
			.catch(error => console.error('Error:', error));



		},
		toogleDarkMode(){
			this.darkMode = !this.darkMode;
			localStorage.setItem('darkMode',this.darkMode);
			this.uploadDarkMode(this.darkMode);
		},
		uploadDarkMode(darkModeValue){
			const data =  new FormData();
			data.append('jwt', this.userCred);
			data.append('wptba_nonce',wptba_nonce );
			data.append('action', 'wptbaUploadDarkMode');
			fetch(wptba_ajax_url,{
				method: 'POST',
				body: data
			})
			.catch(error => console.log(error));
		},
		loadBody(id){
			this.postsToLoad = id;
		},
		disablePassDiag() {
			this.changePassword = false;
			
		}

	},
	mounted(){
		if(localStorage.getItem('darkMode') == 'true'){
			this.darkMode = true;
		}else{
			this.darkMode = false;
		}
		this.getUserDetails();

	}
}
</script>