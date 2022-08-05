import {reactive} from "vue";


export const store = reactive({
    // my_storage_name: ,
    // my_storage_name(){
    //     this.count++
    // }
    title: 'title',
    setTitle(title){
        this.title = title
    }
})
