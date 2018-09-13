<template>
<v-container fluid>
    <v-content>
        <v-card>
            <v-card-title>
                <h1 class="display-3">Списък с пациенти:</h1>
            </v-card-title>
            <v-card-text>
                <v-spacer></v-spacer>
                <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Search"
                        single-line
                ></v-text-field>
                <v-data-table
                        :headers="headers"
                        :items="filteredList"
                        :loading="this.loading"
                >
                    <template slot="items" slot-scope="props">
                        <td @click="openPatient(props.item.pid, props.item)" style="font-size: 14px">
                            <span class="text-lg-left">
                                {{ props.item.fullName }}
                            </span>
                            <span class="text-lg-right">
                                Брой изследвания: {{ props.item.studies.length }}
                            </span>
                        </td>

                    </template>
                    <v-alert slot="no-results" :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </v-data-table>
            </v-card-text>
        </v-card>
    </v-content>
</v-container>
</template>

<script>


    export default {
        name: 'patientList',

        data() {
            return {
                patientListSource: [],
                search: '',
                headers: [
                    {
                        text: 'Име и Фамилия',
                        value: false
                    }
                ],
                loading: false,
                items: [],

            }
        },

       async mounted()  {
           this.loading = true

           axios.get('/api/patients/')
                .then(
                    (data) => {
                        this.patientListSource = data.data
                        this.loading = false
                    }

                )
                .catch(
                    (er) => console.log("error")
                )
        },
        methods: {
            openPatient(id, item) {
                this.$router.push({ name: 'patient-show', params: { id: id, patientName: item.fullName }});

                this.$store.commit('setCurrentPatient', item)
            }
        },
        computed: {
            filteredList() {

                return this.patientListSource.filter(patient => {

                    return patient.fullName.toLowerCase().includes(this.search.toLowerCase())
                })

            }
        }
    }
</script>