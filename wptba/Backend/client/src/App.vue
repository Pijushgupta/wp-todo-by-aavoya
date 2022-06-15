<script setup>
import {ref } from 'vue';
import Header from './components/Header.vue';
import Settings from './components/Settings.vue';
import Accounts from './components/Accounts.vue';

const tabs = ref(
	[
		{name:'Settings'},
		{name:'Pending Accounts'}
	]
);
const tabState = ref(0);



</script>
<template>
	<div class=" flex justify-center my-8 mx-auto">
		<div class="container max-w-7xl border rounded-lg overflow-hidden">
			<Header />
			<div class="app-body flex flex-row text-base bg-white">
				<div class="w-1/5 border-r">
					<ul >
						<li 
						v-for="(tab,index) in tabs" 
						v-bind:key="index" 
						@click="tabState = index" 
						class="mb-0 px-8 py-2 border-b last:border-b-0  cursor-pointer" 
						v-bind:class="tabState == index ? 'bg-gray-400 text-white':'' "> {{tab.name}}</li>
					</ul>
				</div>
				<div class="w-4/5 ">
					<ul>
						<li v-show="tabState == 0">
							<Settings />
						</li>
						<li v-show="tabState == 1">
							<Accounts/>
						</li>
					</ul>
				</div>
				

			</div>
			
			

		</div>
	</div>
</template>