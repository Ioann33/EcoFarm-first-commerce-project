import {reactive} from "vue";

export const store = reactive({
    count: 0,
    increment(){
        this.count++
    },
    my_storage_id: '',
    getMyStorageName(){
         axios.get('/api/getMainStorage/').then(res => {
            console.log(res.data.storage_id)
             this.count++
           this.my_storage_id = res.data.storage_id

        }).catch(err => {
            console.log(err.response)
            console.log(err.response.data.message)
            console.log(err.response.status)
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
        })
    }
})
