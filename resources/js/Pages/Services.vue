<script setup>
import {usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import BookingModal from "@/Pages/Modals/BookingModal.vue";

const props = usePage().props

const activeBookingService = ref(null)
let bookingProcess = ref(false)
const initBooking = (service) => {
    activeBookingService.value = service
    bookingProcess.value = true
}
</script>

<template>
    <div class="services-list">
        <Card
            v-for="(service, index) in props.services"
            style="width: 25rem; overflow: hidden; text-align: center; margin: 10px">
            <template #title>{{ service.name }}</template>

            <template #footer>
                <div class="flex gap-4 mt-1">
                    <Button @click="initBooking(service)" label="Забронировать" class="w-full" />
                </div>
            </template>
        </Card>
    </div>

    <BookingModal @booked="bookingProcess = false"  v-model:visible="bookingProcess" :service="activeBookingService"/>
    <Toast />
</template>

<style>
.services-list {
    display: flex;
    width: 50%;
    justify-content: center;
    margin: 10% auto;
    flex-wrap: wrap;
}
</style>
