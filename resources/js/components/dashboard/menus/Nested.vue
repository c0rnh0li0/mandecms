<template>
    <draggable flat class="dragArea" tag="ul" :list="menus" :group="{ name: 'g1' }">
        <li v-for="el in menus" :key="el.name">
            <p>{{ el.name }}</p>
            <nested-draggable :menus="el.children" />
        </li>
    </draggable>
</template>
<script>
    import draggable from 'vuedraggable';

    export default {
        name: "nested-draggable",
        props: {
            menus: {type: Array, required: false, default: function () { return []; }},
        },
        components: {
            draggable
        },
        mounted() {
            console.log('menus in nested', this.menus);
        }
    };
</script>
<style scoped>
    .dragArea {
        min-height: 50px;
        list-style: none;
        margin: 0;
        padding: 0;
        outline: none;
        border: none;
    }

    .dragArea li {
        outline: 1px solid #cbcbcb;
        margin-bottom: 10px;
        padding: 10px;

        padding-right: 0;
        border-right: none;
    }

    .dragArea ul.dragArea {
        margin-left: 20px;
        border: 1px dashed #f0f0f0;
    }
</style>