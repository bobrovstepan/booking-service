<script setup>
import BookingProcess from "@/Pages/Steppers/BookingProcess.vue";

defineProps({
    service: { type: Object }
})

</script>

<template>
    <Dialog header="Бронирование" :style="{ width: 'auto' }">
        <BookingProcess @booked="$emit('booked')" :service="service"/>
    </Dialog>
</template>
