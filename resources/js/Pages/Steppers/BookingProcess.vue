<script setup>
import {reactive, ref, watch} from "vue";
import axios from "axios";
import {useToast} from "primevue";

const props = defineProps({
    service: { type: Object }
})

const toast = useToast()

const bookingForm = reactive({
    option: null,
    day: null,
    start_time: null
})

watch(bookingForm, () => {
    console.log(bookingForm)
})

const emit = defineEmits('booked')

let minDate = ref(null)
let maxDate = ref(null)

let step = ref('1')

const getAvailableDays = async () => {
    try {
        if (!bookingForm.option) throw new Error('Выберите продолжительность!')

        const resp = await axios.get('/service/available-days', {
            params: {
                data: bookingForm
            }
        })

        minDate.value = new Date(resp.data[0]?.date || null);
        maxDate.value = new Date(resp.data[resp.data.length - 1]?.date || null);

        step.value = '2'
    } catch (error) {
        toast.add({
            severity: 'error',
            detail: error.response?.data.message ?? error.message,
            life: 3000
        })
    }
}

let timeOptions = ref(null)
const getAvailableTimeSlots = async () => {
    try {
        if (!bookingForm.option) throw new Error('Выберите продолжительность!')
        if (!bookingForm.day) throw new Error('Выберите день!')

        // bookingForm.day = bookingForm.day.toISOString().split('T')[0]

        const resp = await axios.get('/service/available-time-slots', {
            params: {
                data: bookingForm
            }
        })

        timeOptions.value = resp.data

        step.value = '3'
    } catch (error) {
        toast.add({
            severity: 'error',
            detail: error.response?.data.message ?? error.message,
            life: 3000
        })
    }
}

const saveBooking = async () => {
    try {
        if (!bookingForm.option) throw new Error('Выберите продолжительность!')
        if (!bookingForm.day) throw new Error('Выберите день!')
        if (!bookingForm.start_time) throw new Error('Выберите время!')

        await axios.post('/booking', {
            data: bookingForm
        })

        bookingForm.option = null
        bookingForm.day = null
        bookingForm.start_time = null

        step.value = '1'

        toast.add({
            severity: 'success',
            detail: 'Успешно забронировано!',
            life: 3000
        })

        emit('booked')
    } catch (error) {
        toast.add({
            severity: 'error',
            detail: error.response?.data.message ?? error.message,
            life: 3000
        })
    }
}


</script>



<template>
    <Stepper :value="step">
        <StepList>
            <Step value="1" @click="step = '1'">Продолжительность</Step>
            <Step value="2" @click="getAvailableDays">День</Step>
            <Step value="3" @click="getAvailableTimeSlots">Время</Step>
            <Step value="4" @click="step = '4'">Информация</Step>
        </StepList>
        <StepPanels>
            <StepPanel v-slot="{ activateCallback }" value="1">
                <div class="stepper-item">
                    <Select v-model="bookingForm.option" :options="service.options" optionLabel="duration_minutes" placeholder="Количество минут" class="w-full md:w-56" />
                </div>

                <div class="flex pt-6 justify-end">
                    <Button label="Дальше" icon="pi pi-arrow-right" iconPos="right" @click="() => { getAvailableDays() }" />
                </div>
            </StepPanel>
            <StepPanel v-slot="{ activateCallback }" value="2">
                <div class="stepper-item">
                    <DatePicker
                        v-model="bookingForm.day"
                        :minDate="minDate"
                        :maxDate="maxDate"
                        :manualInput="false"
                        dateFormat="yy-mm-dd"
                        updateModelType="string"
                    />
                </div>
                <div class="flex pt-6 justify-between">
                    <Button label="Назад" severity="secondary" icon="pi pi-arrow-left" @click="activateCallback('1')" />
                    <Button label="Дальше" icon="pi pi-arrow-right" iconPos="right" @click="() => { getAvailableTimeSlots() }" />
                </div>
            </StepPanel>
            <StepPanel v-slot="{ activateCallback }" value="3">
                <div class="stepper-item">
                    <Select v-model="bookingForm.start_time" :options="timeOptions" placeholder="Время начала" class="w-full md:w-56" />
                </div>
                <div class="pt-6">
                    <Button label="Назад" severity="secondary" icon="pi pi-arrow-left" @click="() => { getAvailableDays() }" />
                    <Button label="Дальше" icon="pi pi-arrow-right" iconPos="right" @click="activateCallback('4')" />
                </div>
            </StepPanel>

            <StepPanel v-slot="{ activateCallback }" value="4">
                <div>
                    <div class="stepper-item">
                        <FloatLabel variant="in">
                            <InputText v-model="bookingForm.user_name" id="name" variant="filled" />
                            <label for="name">Имя</label>
                        </FloatLabel>
                        <FloatLabel variant="in" style="margin-top: 10px">
                            <InputMask id="phone" v-model="bookingForm.user_phone_number" mask="+9 (999) 999-9999" variant="filled" />
                            <label for="phone">Номер телефона</label>
                        </FloatLabel>
                    </div>
                </div>
                <div class="pt-6">
                    <Button label="Назад" severity="secondary" icon="pi pi-arrow-left" @click="() => { getAvailableTimeSlots() }" />
                    <Button label="Забронировать" @click="saveBooking" />
                </div>
            </StepPanel>
        </StepPanels>
    </Stepper>
</template>

<style>
.stepper-item {
    text-align: center;
    margin: 10px;
}

.p-button {
    display: inline-block !important;
}
</style>
