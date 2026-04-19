import { createApp } from 'vue'
import StudentApp from './components/StudentApp.vue'
import MapPicker from './components/MapPicker.vue'
import VerifyStudent from './components/VerifyStudent.vue'
import Toast from './components/Toast.vue'


const el = document.getElementById('app')
const mapEl = document.getElementById('map-app')
const verifyEl = document.getElementById('verify-app')

if (el) {
    const students = JSON.parse(el.dataset.students)

    createApp(StudentApp, {
        initialStudents: students
    }).mount('#app')
}

if (mapEl) {
    createApp(MapPicker, {
        studentId: mapEl.dataset.student
    }).mount('#map-app')
}

if (verifyEl) {
    const student = JSON.parse(verifyEl.dataset.student)

    createApp(VerifyStudent, {
        student: student
    }).mount('#verify-app')
}

const app = createApp({})

const toastApp = createApp(Toast)
const toastInstance = toastApp.mount(document.createElement('div'))

document.body.appendChild(toastInstance.$el)

// global function
window.$toast = (msg, type = 'success') => {
    toastInstance.show(msg, type)
}