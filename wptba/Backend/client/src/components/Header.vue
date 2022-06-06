<template>
<div class="w-full header" v-bind:style="{backgroundImage: 'url('+header+'/img/header.jpg)'}">
	<div class=" w-full  py-16  px-6 backdrop-blur">
		<div class="prose ">
			<h1 class="mb-2 text-white">WP Todo</h1>
			<a class="text-white hover:text-white no-underline " href="https://aavoya.co" target="_blank">By Aavoya</a>
		</div>
	</div>
</div>	
</template>
<script setup>
let header = wptba_dist_path;
</script>