import Vue from 'vue'
import Vuex from 'vuex'

VUe.use(Vuex)

// 设置状态
const  state = {
    notes:[], // 笔记列表
    activeNote: {} // 当前笔记
}

// 所有操作
const mutations = {
    ADD_NOTE(state) { // 添加笔记
        const newNote = {
            text:'new Note', // 添加笔记的内容text属性
            favorite:false // 收藏（否）
        }
        state.notes.push(newNote); // 声明的newNote添加到之前声明好的state.notes
        state.activeNote = newNote // 设为当前笔记
    },
    EDIT_NOTE(state, text) { // 编辑当前笔记，让当前笔记的内容=传入的text参数
        state.activeNote.text = text
    },
    DELETE_NODE(state){
        state.notes.$remove(state.activeNote); // 删除当前笔记
        state.activeNote = state.notes[0]; // 使得当前笔记等于列表中的第一个
    },
    TOGGLE_FAVORITE(state){ // 切换收藏与取消收藏
        state.activeNote.favorite = !state.activeNote.favorite
    },
    SET_ACTIVE_NOTE(state, note) {
        state.activeNote = note // 设置为传入的note为当前笔记
    }
}

// 导出一上设置
export default new Vuex.Store({
    state,
    mutations
})