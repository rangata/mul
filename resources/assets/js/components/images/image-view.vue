<template>
    <v-container>
        <v-content>
            <!--<img :src="getImage(currentImage)" alt="">-->
        </v-content>
    </v-container>
</template>

<script>
    export default {
        name: "image-view",


        data() {
            return {
                imageData: {}
            }
        },
        mounted() {
            console.log(this.$route.params.instance)
        },
        methods: {

            encodeImage(arrayBuffer) {
                let u8 = new Uint8Array(arrayBuffer)
                let b64encoded = btoa([].reduce.call(new Uint8Array(arrayBuffer),function(p,c){return p+String.fromCharCode(c)},''))
                let mimetype="image/png"
                return "data:"+mimetype+";base64,"+b64encoded
            },
            getImage(id) {
                return axios.get('/api/instances/' + id + '/preview', { responseType: 'arraybuffer' })
                    .then((response) => {
                        let image = btoa(
                            new Uint8Array(response.data)
                                .reduce((data, byte) => data + String.fromCharCode(byte), '')
                        );
                        return `data:image/png;base64,${image}`;
                    });
            }
        }
    }
</script>

<style scoped>

</style>