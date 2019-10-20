<template>
	<section class="content-header" :titulo="titulo">
		<h1>
			{{titulo}}			
			<small> {{ descricao }}</small>			
 		</h1>
		<ol class="breadcrumb">
			<li v-for="item in migalha">
				<a :href="item.url">
					<i :class="'fa fa-'+item.icone" ></i>{{ item.nome }}
				</a>
			</li>
		</ol>
	</section>
</template>
<script>
	export default {
	 	props:['titulo', 'migalha', 'descricao'],
		data () {
            return {
               data:[],
            
            }
        },
        
    };
</script>