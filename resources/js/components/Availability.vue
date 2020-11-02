<template>

</template>

<script>
import {mapGetters} from 'vuex'

export default {
    name: "Availability",
    props: {datesOfWeek},
    methods: {
        deleteAvailability(availability) {
            this.$store.dispatch('deleteAvailability',availability)
        },
        createAvailability(availability) {
            this.$store.dispatch('createAvailability', availability)
        },
        getInputIdentifier(date) {
            return 'toggle['+date+']';
        },
        getInputName(date) {
            return 'aanwezig['+date+']';
        }
    }
}
</script>

<style scoped>

</style>
