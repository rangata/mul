import list from './components/patients/list'
import patientShow from './components/patients/patient-show'
import imageView from './components/images/image-view';
import studieView from './components/images/studieView';

export const routes = [
    {
        path: '/',
        name: 'patient-list',
        component: list
    },
    {
        path: '/patients/:id',
        name: 'patient-show',
        props: true,
        component: patientShow
    },
    {
        path: '/patients/:id/studie/:studieId',
        name: 'studieView',
        props: true,
        component: studieView
    },
    {
        path: '/patients/:id/showImage/:instanceId',
        name: 'previewImage',
        props: true,
        component: imageView
    }
]