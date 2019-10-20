<template>
	<ul class="dropdown-menu">
        <li role="presentation" v-if="can.conteudo_editar">
            <a role="menuitem" :href="'/base-de-conhecimento/publicacoes/'+publicacaoId+'/edit'">&nbsp;Conteúdo</a>
        </li> 
        <li role="presentation" v-if="can.editar">
           <vc-modal-link texto="Dados da publicação" modal="publicacao-modal"></vc-modal-link>
        </li>
        <li role="presentation" v-if="can.editar">
           <vc-modal-link texto="Imagem da capa" modal="publicacao-imagem-capa-modal"></vc-modal-link>
        </li>
        <li role="presentation" v-if="can.editar">
           <vc-modal-link texto="Publicação" modal="publicacao-datas-modal"></vc-modal-link>
        </li>
        <li role="presentation" v-if="can.rascunho">
            <a role="menuitem" :href="'/base-de-conhecimento/publicacoes/'+publicacaoId+'/rascunho'">&nbsp;Rascunho</a>
        </li> 
        <li role="presentation" v-if="can.restricao">
           <vc-modal-link texto="Restrições de acesso" modal="publicacao-restricao-modal"></vc-modal-link>
        </li>
    </ul>
</template>
<script>
	export default {
		props:['publicacaoId', 'can'],
		data(){
			return { 
				
			}
		},
	};
</script>