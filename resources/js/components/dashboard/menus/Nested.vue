<template>
    <div>
        <draggable class="dragArea"
                   ref="soratblemenu"
                   tag="ul"
                   :list="menus"
                   :group="{ name: 'menuitems' }"
                   :move="onMove"
                   @start="dragStart"
                   @end="dragEnd"
                   handle=".handle">
            <li v-for="el in menus" :key="el.id">
                <div class="menu-info">
                    <div class="text-xs-start menu-name">
                    <span class="menuname">
                        <i class="fa fa-align-justify handle"></i>
                        {{ el.name }}
                    </span>
                        <small>
                            ({{ el.slug }})
                        </small>
                    </div>
                    <div class="menu-actions">
                        <v-icon small class="mr-2" @click="editItem(el)">edit</v-icon>
                        <v-icon small class="mr-2" @click="deleteItem(el)">delete</v-icon>
                    </div>
                </div>


                <v-spacer></v-spacer>

                <nested-draggable :menus="el.children"></nested-draggable>
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
            editItem(item) {
                this.$emit('editItem', item);
            },
            deleteItem(item) {
                this.$emit('deleteItem', item);
            },
            dragStart() {
                this.isDragging = true;
            },
            dragEnd() {
                this.isDragging = false;
                this.$emit('menuchanged', true);
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
        border: 1px dashed #efaeae;
    }

    .dragArea li {
        padding: 10px 0 0 0;
    }

    .dragArea * ul.dragArea {
        border-right: none;
    }

    .menu-actions, .menu-name {
        display: inline-block;
        position: relative;
    }

    .menu-actions {
        float: right;
    }

    .menu-info {
        padding: 5px 0 10px 0;
    }

    .handle {
        cursor: move;
    }
</style>