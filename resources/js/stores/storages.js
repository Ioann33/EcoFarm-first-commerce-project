import { defineStore } from 'pinia'
export const useStorage = defineStore({
    'id': 'Storage',
    state: () => {
        return {
            storages_id: [],
            store_message: 'test message',
            storage_id: null
        }
    },
    getters: {
        my_storage_id: (state) => {

            return 2//this.storage_id
        }
    },
    actions: {
        setMyStorage(r){
            console.log(r)
        },
        async getMyStorages() {

            // this.store_message = 'new test message'
            // this.storages_id = await fetch('/api/getMyStorage')
            //     .then((response) => response.json())

            await axios.get('/api/getListMyStorages').then(res => {
                this.storages_id = res.data.data
                this.store_message = 'new test message2'

            }).catch(err => {
                console.log(err.response)
                console.log(err.response.data.message)
                console.log(err.response.status)
                this.store_message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })
        }
    }
})
