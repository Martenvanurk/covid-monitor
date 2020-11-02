let mutations = {
    CREATE_AVAILABILITY(state, availability) {
        state.availability.unshift(availability)
    },
    FETCH_AVAILABILITY(state, availability) {
        return state.availability = availability
    },
    DELETE_AVAILABILITY(state, availability) {
        let index = state.availability.findIndex(item => item.id === availability.id)
        state.availability.splice(index, 1)
    }

}
export default mutations
