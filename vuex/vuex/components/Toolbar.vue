<template>
    <div id="toolbar">
        <i @click="addNote" class="glyphicon glyphicon-plus"></i>
        <i @click="toggleFavorite"
           class="glyphicon glyphicon-star"
           :class="{starred: activeNote.favorite}"></i>
        <i @click="deleteNote" class="glyphicon glyphicon-remove"></i>
    </div>
</template>
<script>
    import {addNote,deleteNote,toggleFavorite} from '../action'

    export default{
        vuex: {
            getters:{
                activeNote: function(state){
                    state.activeNote
                }
            },
            actions:{
                addNote,
                deleteNote,
                toggleFavorite
            }
        }
    }
</script>