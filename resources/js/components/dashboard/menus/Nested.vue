<template>
    <div>
        <draggable class="dragArea"
                   ref="soratblemenu"
                   tag="ul"
                   :list="menus"
                   :group="{ name: 'menuitems' }"
                   :move="onMove"
                   @start="dragStart" @end="dragEnd"
                   handle=".handle">
            <li v-for="el in menus" :key="el.id">
                <p>
                    <i class="fa fa-align-justify handle"></i>
                    <span class="menuname">{{ el.name }}</span>
                </p>
                <nested-draggable :menus="el.children" />
            </li>
        </draggable>
    </div>
</template>
<script>
    import draggable from 'vuedraggable';

    export default {
        name: "nested-draggable",
        props: {
            menus: {type: Array, required: false, default: function () { return []; }},
        },
        data() {
            return {
                isDragging: false,
                delayedDragging: false,
            }
        },
        components: {
            draggable
        },
        watch: {
            isDragging(newValue) {
                if (newValue) {
                    this.delayedDragging = true;
                    return;
                }
                this.$nextTick(() => {
                    this.delayedDragging = false;
                });
            }
        },
        mounted() {},
        methods: {
            dragStart() {
                this.isDragging = true;
            },
            dragEnd() {
                this.isDragging = false;
            },
            serialize() {
                this.$emit('sort', JSON.stringify(this.menus, null, 2));
            },
            orderList() {
                this.menus = this.menus.sort((one, two) => {
                    return one.order - two.order;
                });
            },
            onMove({ relatedContext, draggedContext }) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;
                return (
                    (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
                );
            }
        }
    };
</script>
<style scoped>
    .dragArea {
        min-height: 50px;
        list-style: none;
        border: 1px solid #efaeae;
    }

    .dragArea * ul.dragArea {
        border-right: none;
    }

    .menuname {
        padding: 10px 0 10px 0;
    }

    .handle {
        cursor: move;
    }
</style>