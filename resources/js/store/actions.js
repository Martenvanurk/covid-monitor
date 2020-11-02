let actions = {
    createAvailability({commit}, availability) {
        axios.post('/api/posts', post)
            .then(res => {
                commit('CREATE_AVAILABILITY', res.data)
            }).catch(err => {
            console.log(err)
        })

    },
    fetchAvailability({commit}) {
        axios.get('/api/posts')
            .then(res => {
                commit('FETCH_AVAILABILITY', res.data)
            }).catch(err => {
            console.log(err)
        })
    },
    deleteAvailability({commit}, post) {
        axios.delete(`/api/posts/${post.id}`)
            .then(res => {
                if (res.data === 'ok')
                    commit('DELETE_AVAILABILITY', post)
            }).catch(err => {
            console.log(err)
        })
    }
}

export default actions
