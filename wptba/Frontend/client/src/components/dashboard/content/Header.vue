<template>
	<header class="dark:bg-gray-800 dark:text-gray-400 bg-gray-200 text-gray-500 dark:border-b-black border-b-gray-400 font-semibold ">
		<div class="container-full">
			<div class="flex justify-between items-center py-2 px-5">
				<div class="w-6/12">Wp Todo</div>
				
				<div class="w-6/12 flex justify-end relative">
					<div class="rounded-full dark:bg-gray-900 bg-gray-400 p-1 flex justify-center items-center cursor-pointer " @click="showPreference = !showPreference">
						<span class="flex justify-center items-center w-7 h-7">{{userLogo}}</span>
						
					</div>
					<div v-show="showPreference" class="menu absolute top-14 w-56 bg-white dark:bg-gray-900 border dark:border-gray-600 cursor-default rounded-lg font-normal ">
							<ul>
								<li class="flex flex-row justify-between cursor-pointer px-6 pt-2 last:pb-2 ">{{displayName}}</li>
								<li class="flex flex-row justify-between cursor-pointer px-6 pt-2 last:pb-2 ">
									<div>Dark Mode</div>
									<div>
										<input 
										type="checkbox" 
										@change="$emit('tooglemode')" 
										class="cursor-pointer" 
										v-if="darkMode != null" 
										v-model="darkMode"
										>
									</div>
								</li>
								<li class="flex flex-row justify-between cursor-pointer px-6 pt-2 last:pb-2 ">
									<div @click="$emit('logout')">Logout</div>
								</li>
							</ul>
						</div>
				</div>
			</div>
		</div>
	</header>
</template>
<script setup>
import {ref, onMounted} from 'vue';
const props = defineProps({
	userProfile: Object,
	darkMode: Boolean

})

const profile = ref();
const userLogo = ref();
const displayName = ref();
const darkMode = ref(null);
const showPreference = ref(false);



onMounted(() => {
	profile.value = props.userProfile[0]
	let nameArray = profile.value.displayName.split(" ");
	userLogo.value = nameArray[0].charAt(0).toUpperCase() + nameArray[nameArray.length - 1 ].charAt(0).toUpperCase();
	displayName.value = profile.value.displayName;
	darkMode.value = props.darkMode;
});













</script>