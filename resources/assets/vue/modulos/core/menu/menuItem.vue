<template>
    <li :class="'treeview '+menuItem.active">
        <a :href="menuItem.url">
            <i :class="'fa fa-'+menuItem.icone"></i> <span class="">{{menuItem.nome}}</span>
            <span class="pull-right-container" v-if="menuItem.parente">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
    
        <ul class="treeview-menu" v-if="menuItem.parente">
            <vc-menu-item v-for="(menu, index) in menuItem.parente" :menuItem="menu" :key="index"></vc-menu-item>
        </ul>
    </li>
</template>

<script>
export default {
    props:['menuItem'],
    data () {
        return {
            menuItemObj: {},
        }
    },
    methods:
    {
        
    },
    mounted()
    {

    },
};
</script>