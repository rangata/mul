<template>
    <v-container grid-list-md>
        <v-content>
            <v-card>
                <v-card-title>
                        <h1>{{ patientName }}</h1>
                </v-card-title>
                <v-card-text>
                    <h3>
                        Изследвания
                    </h3>
                </v-card-text>
            </v-card>
            <v-layout row>
                <v-flex lg12>
                    <v-container>
                    </v-container>

                    <v-card v-for="(studie,key) in this.studies" :key="studie.ID">
                        <!--{{ getInstance(studie.ID) }}-->
                        <!--<v-container class="" v-for="(inst, instkey) in instances[key]">-->
                            <!--<v-card-media :src="'data:image/png;base64,' + images[key]" height="400px" fill-height contain :key="instkey" ></v-card-media>-->
                        <!--</v-container>-->

                        <div>
                            <v-progress-linear :indeterminate="loading"></v-progress-linear>
                            <v-card-media>
                                <img :src="studie.instanc" :key="key" fill-height contain />
                            </v-card-media>
                        </div>

                        <v-card-title primary-title>
                            <div class="patientInfo">
                                <h3 class="headline mb-0">{{studie.ID}}</h3>
                                <div>
                                    <span>Дата на изследването:</span><br>
                                    {{ normalizeDate(studie.MainDicomTags.StudyDate)}} | <span>час: {{ normalizeTime(studie.MainDicomTags.StudyTime) }}</span>
                                </div>
                            </div>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-spacer></v-spacer>
                        <v-card-actions>
                            <v-btn flat color="success" @click="navigateTo(studie.ID)">Отвори</v-btn>
                        </v-card-actions>
                    </v-card>
                    <v-spacer></v-spacer>
                </v-flex>
            </v-layout>




        </v-content>
    </v-container>
</template>

<script>
    import VImg from 'vuetify';
    import Hammer from 'hammerjs';
    import * as cornerstone from 'cornerstone-core';
    import * as cornerstoneMath from 'cornerstone-math';
    import * as cornerstoneWebImageLoader from 'cornerstone-web-image-loader';
    import * as cornerstoneTools from 'cornerstone-tools';

    cornerstoneTools.external.Hammer = Hammer;
    cornerstoneTools.external.cornerstone = cornerstone;
    cornerstoneWebImageLoader.external.cornerstone = cornerstone;
    cornerstoneTools.external.cornerstoneMath = cornerstoneMath;

    export default {
        name: "patient-show",
        data() {
            return {
                localId: this.$route.params.id,
                patientInfo: {},
                patientName: '',
                patientStudies: '',
                studies: [],
                series: [],
                instances: [],
                images: [],
                loading: true,
                width: this.$vuetify.breakpoint.width - 150 + 'px',
                height: this.$vuetify.breakpoint.height - 150    + 'px'
            }
        },
        mounted () {

            // console.log(this.$store.state.patient)
            this.patientName = this.$store.state.patient.fullName
            this.patientStudies = this.$store.state.patient.studies

            // console.log(this.patientStudies)
            let lteObj = [];

            this.patientStudies.forEach((studie, key) => {
                let skey = key
                axios.get('/api/studies/' + studie)
                    .then(
                        (data) => {
                            
                           // this.studies.push(data.data);
                            lteObj = data.data
                            lteObj['instanc'] = [];
                            // lteObj.instanc.push('aa');

                            axios.get('/api/studies/' + lteObj.ID + '/instances')
                                .then(
                                    (data) => {
                                        data.data.forEach(id => {
                                            axios.get('/api/instances/' + id.ID + '/preview', {responseType: 'arraybuffer'})
                                                .then(
                                                    (data) => {
                                                        this.studies[skey]['instanc'] = 'data:image/png;base64, ' + new Buffer.from(data.data, 'binary').toString('base64')
                                                    }
                                                )

                                        })
                                    }
                                )
                            this.studies.push(lteObj)
                        }

                    )
                    .then()
                this.loading = false;
            });


        },


        methods: {
            getInstance(id) {
              return axios.get('/api/studies/' + id + '/instances')
                  .then(
                      (data) => {
                          return this.instances.push(data.data)
                      }
                  )
                  .catch(
                      (er) =>console.log(er)
                  )
            },
            navigateTo(studieId) {
              // this.$router.push({
              //     name: 'previewImage',
              //     params: {
              //         instanceId: instanceId,
              //         instance: instanceImage
              //     }
              // })
                this.$router.push({
                    name: 'studieView',
                    params: {
                        studieId: studieId
                    }
                })
            },
            getImage(id) {
                axios.get('/api/instances/' + id + '/preview', { responseType: 'arraybuffer'})
                    .then(
                        (response => {
                            console.log( new Buffer.from(response.data, 'binary').toString('base64'))
                            }
                        )


                    )
                    .catch(
                        (er) => console.error(er)
                    )
            },

            encodeImage(arrayBuffer) {
                let u8 = new Uint8Array(arrayBuffer)
                let b64encoded = btoa([].reduce.call(new Uint8Array(arrayBuffer),function(p,c){return p+String.fromCharCode(c)},''))
                let mimetype="image/png"
                return "data:"+mimetype+";base64,"+b64encoded
            },

            getSeries(seriesId) {

                axios.get('/api/series/' + seriesId)
                    .then(
                        (data) => this.series = data.data
                    )
                    .catch()

            },
            normalizeName(value) {
                return (value.replace('^', ' ').replace('^^^','')).split(" ")[1] + ' ' + (value.replace('^', ' ').replace('^^^','')).split(" ")[0]

            },
            normalizeDate(value) {
                let year = value.slice(0,4);

                let month = value.slice(4,-2);
                let day = value.slice(6);

                return day + '.' + month + '.' + year
            },
            normalizeTime(value) {
                let hours = value.slice(0, 2);
                let minutes = value.slice(2, -2);
                let seconds = value.slice(4)

                return hours + ':' + minutes + ':' + seconds;
            }
        },

    }
</script>

<style scoped>

</style>